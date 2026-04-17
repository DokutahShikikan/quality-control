<x-layout title="Редактирование набора" current="datasets">
    <div class="space-y-8">
        <div class="panel">
            <h2 class="panel-title">Редактирование набора</h2>
            <form method="POST" action="/datasets/{{$dataset->id}}" class="mt-8 space-y-6">
                @csrf
                @method('PATCH')

                <div class="form-field">
                    <label for="description" class="form-label">Описание набора и сценария автоисправления</label>
                    <textarea id="description" name="description" rows="5" class="text-area">{{ trim($dataset->description) }}</textarea>
                    <x-forms.error name="description"/>
                </div>

                <div class="flex flex-wrap gap-4">
                    <button type="submit" class="primary-button">Сохранить изменения</button>
                    <button type="submit" form="delete-dataset-form" class="danger-button">Удалить набор</button>
                    <a href="/datasets/{{$dataset->id}}" class="secondary-button">Назад</a>
                </div>
            </form>
        </div>

        <form id="delete-dataset-form" method="POST" action="/datasets/{{$dataset->id}}">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-layout>
