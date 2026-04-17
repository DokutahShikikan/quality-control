<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DatasetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:120'],
            'description' => ['nullable', 'max:2000'],
            'source_file' => ['required', 'file', 'mimes:csv,txt,xlsx', 'max:5120'],
            'deepseek_enabled' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название набора данных обязательно.',
            'name.min' => 'Название набора данных должно содержать не менее 3 символов.',
            'source_file.required' => 'Нужно загрузить CSV или XLSX файл.',
            'source_file.mimes' => 'Поддерживаются только CSV, TXT и XLSX файлы.',
        ];
    }
}
