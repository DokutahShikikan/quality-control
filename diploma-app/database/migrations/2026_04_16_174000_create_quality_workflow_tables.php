<?php

use App\Models\Dataset;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dataset_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dataset::class)->constrained()->cascadeOnDelete();
            $table->unsignedInteger('row_index');
            $table->json('payload');
            $table->string('fingerprint')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('quality_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('issue_type');
            $table->string('severity')->default('medium');
            $table->text('description')->nullable();
            $table->string('pattern')->nullable();
            $table->string('replacement')->nullable();
            $table->json('column_hints')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('check_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dataset::class)->constrained()->cascadeOnDelete();
            $table->string('status')->default('completed');
            $table->string('trigger_source')->default('import');
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('issues_count')->default(0);
            $table->unsignedInteger('duplicate_pairs_count')->default(0);
            $table->json('summary')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dataset::class)->constrained()->cascadeOnDelete();
            $table->foreignId('check_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dataset_row_id')->nullable()->constrained()->nullOnDelete();
            $table->string('column_name')->nullable();
            $table->string('issue_type');
            $table->string('severity')->default('medium');
            $table->string('title');
            $table->text('message');
            $table->text('original_value')->nullable();
            $table->text('suggested_value')->nullable();
            $table->string('suggestion_source')->default('regex');
            $table->string('status')->default('open');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('duplicate_candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dataset::class)->constrained()->cascadeOnDelete();
            $table->foreignId('check_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('primary_row_id')->constrained('dataset_rows')->cascadeOnDelete();
            $table->foreignId('duplicate_row_id')->constrained('dataset_rows')->cascadeOnDelete();
            $table->decimal('confidence', 5, 2)->default(1.00);
            $table->text('rationale')->nullable();
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('duplicate_candidates');
        Schema::dropIfExists('issues');
        Schema::dropIfExists('check_runs');
        Schema::dropIfExists('quality_rules');
        Schema::dropIfExists('dataset_rows');
    }
};
