<?php

namespace Database\Seeders;

use App\Models\QualityRule;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        QualityRule::query()->insert([
            [
                'name' => 'Email format',
                'issue_type' => 'invalid_format',
                'severity' => 'high',
                'description' => 'Проверяет адреса электронной почты и предлагает lowercase/trim нормализацию.',
                'pattern' => '^[^\s@]+@[^\s@]+\.[^\s@]+$',
                'replacement' => null,
                'column_hints' => json_encode(['email', 'e-mail', 'почта'], JSON_UNESCAPED_UNICODE),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Phone format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет телефонные номера и пытается привести их к международному виду.',
                'pattern' => '^\+?\d[\d\-\(\)\s]{9,}$',
                'replacement' => null,
                'column_hints' => json_encode(['phone', 'tel', 'mobile', 'телефон'], JSON_UNESCAPED_UNICODE),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Date format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет даты и нормализует их к ISO-формату YYYY-MM-DD.',
                'pattern' => '^\d{4}-\d{2}-\d{2}$',
                'replacement' => null,
                'column_hints' => json_encode(['date', 'birth', 'created', 'дата'], JSON_UNESCAPED_UNICODE),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
