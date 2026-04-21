<?php

namespace Tests\Feature;

use App\Models\Dataset;
use App\Models\DatasetRow;
use App\Models\DuplicateCandidate;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DatasetImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_import_dataset_and_receive_detected_issues(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $file = UploadedFile::fake()->createWithContent(
            'clients.csv',
            implode("\n", [
                'email,phone,date',
                'bad mail,8 (999) 555-55-55,31.12.2025',
                'bad mail,8 (999) 555-55-55,31.12.2025',
            ])
        );

        $response = $this->actingAs($user)->post('/datasets', [
            'name' => 'Тестовый набор',
            'description' => 'Проверка импорта',
            'source_file' => $file,
        ]);

        $dataset = Dataset::query()->first();

        $response->assertRedirect("/datasets/{$dataset->id}");
        $this->assertDatabaseCount('datasets', 1);
        $this->assertDatabaseHas('issues', ['dataset_id' => $dataset->id]);
        $this->assertDatabaseHas('duplicate_candidates', ['dataset_id' => $dataset->id]);
        $this->assertGreaterThan(0, Issue::query()->count());
        $this->assertGreaterThan(0, DuplicateCandidate::query()->count());
    }

    public function test_import_detects_invalid_numeric_values(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $file = UploadedFile::fake()->createWithContent(
            'salary.csv',
            implode("\n", [
                'full_name,salary,status',
                'Анна Иванова,55000,active',
                'Иван Петров,not_a_number,pending',
            ])
        );

        $this->actingAs($user)->post('/datasets', [
            'name' => 'Зарплаты',
            'description' => 'Проверка числовых полей',
            'source_file' => $file,
        ]);

        $this->assertDatabaseHas('issues', [
            'column_name' => 'salary',
            'title' => 'Numeric value',
            'original_value' => 'not_a_number',
        ]);
    }

    public function test_user_is_redirected_to_dataset_list_after_delete(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $dataset = Dataset::query()->create([
            'user_id' => $user->id,
            'name' => 'Тестовый набор',
            'description' => 'Набор для удаления',
            'source_filename' => 'clients.csv',
            'source_path' => 'imports/test.csv',
            'source_mime' => 'text/csv',
            'import_status' => 'ready',
            'review_status' => 'needs_review',
            'headers' => ['email'],
            'metrics' => [],
        ]);

        $response = $this->actingAs($user)->delete("/datasets/{$dataset->id}");

        $response->assertRedirect('/datasets');
        $response->assertStatus(303);
        $this->assertDatabaseMissing('datasets', ['id' => $dataset->id]);
    }

    public function test_import_detects_duplicates_by_email(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $file = UploadedFile::fake()->createWithContent(
            'duplicates.csv',
            implode("\n", [
                'record_id,full_name,email,phone,status',
                'A-001,Анна Иванова,shared@example.com,+7 (999) 111-22-33,active',
                'A-002,Иван Петров,shared@example.com,+7 (999) 444-55-66,pending',
            ])
        );

        $this->actingAs($user)->post('/datasets', [
            'name' => 'Дубли по email',
            'description' => 'Проверка дублей',
            'source_file' => $file,
        ]);

        $this->assertDatabaseHas('duplicate_candidates', [
            'rationale' => 'Совпадает адрес электронной почты.',
        ]);
    }

    public function test_dataset_live_panels_endpoint_returns_partial_html(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $dataset = Dataset::query()->create([
            'user_id' => $user->id,
            'name' => 'Живое обновление',
            'description' => 'Проверка частичного обновления',
            'source_filename' => 'demo.csv',
            'source_path' => 'imports/demo.csv',
            'source_mime' => 'text/csv',
            'import_status' => 'ready',
            'review_status' => 'needs_review',
            'headers' => ['email'],
            'metrics' => ['open_issues' => 1, 'open_duplicates' => 1],
        ]);

        $dataset->issues()->create([
            'check_run_id' => $dataset->checkRuns()->create(['status' => 'completed'])->id,
            'issue_type' => 'invalid_format',
            'severity' => 'high',
            'title' => 'Неверный адрес',
            'message' => 'Ошибка в почте',
            'column_name' => 'email',
            'original_value' => 'bad mail',
            'status' => 'open',
        ]);

        $response = $this->actingAs($user)->getJson("/datasets/{$dataset->id}/live-panels");

        $response->assertOk();
        $response->assertJsonStructure(['issuesHtml', 'duplicatesHtml', 'statsHtml']);
        $this->assertStringContainsString('Последние ошибки', $response->json('issuesHtml'));
    }

    public function test_deepseek_autofix_updates_values_and_rechecks_dataset(): void
    {
        $this->seed();

        config()->set('services.deepseek.api_key', 'test-key');
        config()->set('services.deepseek.base_url', 'https://api.deepseek.com');
        config()->set('services.deepseek.model', 'deepseek-chat');

        Http::fake([
            'https://api.deepseek.com/chat/completions' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode([
                            'corrected_value' => 'active',
                            'should_fix' => true,
                            'reason' => 'Normalized status typo.',
                            'confidence' => 'high',
                        ], JSON_UNESCAPED_UNICODE),
                    ],
                ]],
            ], 200),
        ]);

        $user = User::factory()->create();
        $dataset = Dataset::query()->create([
            'user_id' => $user->id,
            'name' => 'Проверка DeepSeek',
            'description' => 'Таблица для ИИ-исправления',
            'source_filename' => 'clients.csv',
            'source_path' => 'imports/clients.csv',
            'source_mime' => 'text/csv',
            'import_status' => 'ready',
            'review_status' => 'needs_review',
            'headers' => ['status'],
            'total_rows' => 1,
            'total_columns' => 1,
            'deepseek_enabled' => true,
            'metrics' => [
                'open_issues' => 1,
                'fixable_issues' => 0,
                'open_duplicates' => 0,
                'deepseek_stage_ready' => true,
            ],
        ]);

        $row = DatasetRow::query()->create([
            'dataset_id' => $dataset->id,
            'row_index' => 1,
            'payload' => ['status' => 'actve'],
            'is_active' => true,
        ]);

        $run = $dataset->checkRuns()->create([
            'status' => 'completed',
            'trigger_source' => 'manual',
            'total_rows' => 1,
            'issues_count' => 1,
        ]);

        Issue::query()->create([
            'dataset_id' => $dataset->id,
            'check_run_id' => $run->id,
            'dataset_row_id' => $row->id,
            'column_name' => 'status',
            'issue_type' => 'invalid_format',
            'severity' => 'medium',
            'title' => 'Status format',
            'message' => 'Статус не соответствует допустимым значениям.',
            'original_value' => 'actve',
            'suggested_value' => null,
            'suggestion_source' => 'regex',
            'status' => 'open',
            'meta' => ['row_index' => 1],
        ]);

        $response = $this->actingAs($user)->post("/autofix/{$dataset->id}");

        $response->assertRedirect();

        $row->refresh();
        $dataset->refresh();

        $this->assertSame('active', $row->payload['status']);
        $this->assertSame(0, (int) data_get($dataset->metrics, 'open_issues', 0));
        $this->assertDatabaseHas('check_runs', [
            'dataset_id' => $dataset->id,
            'trigger_source' => 'deepseek_fix',
        ]);
    }

    public function test_fixing_missing_issue_redirects_back_to_list_instead_of_404(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/issues/999999/fix');

        $response->assertRedirect('/issues');
        $response->assertSessionHas('error');
    }
}
