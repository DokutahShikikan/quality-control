<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('source_filename');
            $table->string('source_mime')->nullable();
            $table->string('import_status')->default('ready');
            $table->string('review_status')->default('needs_review');
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('total_columns')->default(0);
            $table->json('headers')->nullable();
            $table->json('metrics')->nullable();
            $table->boolean('deepseek_enabled')->default(false);
            $table->timestamp('last_checked_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
