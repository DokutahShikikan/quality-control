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

        $rules = [
            [
                'name' => 'Email format',
                'issue_type' => 'invalid_format',
                'severity' => 'high',
                'description' => 'Проверяет адреса электронной почты и предлагает lowercase/trim нормализацию.',
                'pattern' => '^[^\s@]+@[^\s@]+\.[^\s@]+$',
                'replacement' => null,
                'column_hints' => ['email', 'e-mail', 'почта'],
            ],
            [
                'name' => 'Phone format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет телефонные номера и пытается привести их к международному виду.',
                'pattern' => '^\+?\d[\d\-\(\)\s]{9,}$',
                'replacement' => null,
                'column_hints' => ['phone', 'tel', 'mobile', 'телефон'],
            ],
            [
                'name' => 'Date format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет даты и нормализует их к ISO-формату YYYY-MM-DD.',
                'pattern' => '^\d{4}-\d{2}-\d{2}$',
                'replacement' => null,
                'column_hints' => ['date', 'birth', 'created', 'дата'],
            ],
            [
                'name' => 'Numeric value',
                'issue_type' => 'invalid_format',
                'severity' => 'high',
                'description' => 'Проверяет числовые поля, например salary, amount, total, score или price.',
                'pattern' => '^-?\d+(?:[.,]\d+)?$',
                'replacement' => null,
                'column_hints' => ['salary', 'amount', 'price', 'cost', 'total', 'score', 'qty', 'quantity', 'sum'],
            ],
            [
                'name' => 'Status format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет, что статус соответствует одному из допустимых значений.',
                'pattern' => '^(active|inactive|pending)$',
                'replacement' => null,
                'column_hints' => ['status', 'state', 'статус'],
            ],
        ];

        foreach ($rules as $rule) {
            QualityRule::query()->updateOrCreate(
                ['name' => $rule['name']],
                [
                    ...$rule,
                    'column_hints' => $rule['column_hints'],
                    'is_active' => true,
                ]
            );
        }
    }
}
