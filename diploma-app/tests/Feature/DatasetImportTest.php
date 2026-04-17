<?php

namespace Tests\Feature;

use App\Models\Dataset;
use App\Models\DuplicateCandidate;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
}
