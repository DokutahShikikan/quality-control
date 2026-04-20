<x-layout title="Редактирование таблицы" current="datasets">
    <div class="space-y-8">
        <div class="panel">
            <x-section-header title="Редактирование таблицы" />

            <form method="POST" action="/datasets/{{$dataset->id}}" class="mt-8 space-y-6">
                @csrf
                @method('PATCH')

                <x-forms.textarea-field
                    name="description"
                    label="Описание таблицы и сценария автоисправления"
                    :value="trim($dataset->description)"
                />

                <x-form-actions>
                    <button type="submit" class="primary-button">Сохранить изменения</button>
                    <button type="submit" form="delete-dataset-form" class="danger-button">Удалить таблицу</button>
                    <a href="/datasets/{{$dataset->id}}" class="secondary-button">Назад</a>
                </x-form-actions>
            </form>
        </div>

        <form id="delete-dataset-form" method="POST" action="/datasets/{{$dataset->id}}">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layout>
