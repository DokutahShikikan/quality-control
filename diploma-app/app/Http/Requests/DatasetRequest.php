<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatasetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'source_file' => ['required', 'file', 'mimes:csv,txt,xlsx,xls', 'max:20480'],
            'deepseek_enabled' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название таблицы обязательно.',
            'name.min' => 'Название таблицы должно содержать не менее 3 символов.',
            'source_file.required' => 'Выберите файл таблицы.',
            'source_file.mimes' => 'Поддерживаются только CSV и Excel файлы.',
            'source_file.max' => 'Файл слишком большой. Максимальный размер: 20 МБ.',
        ];
    }
}
