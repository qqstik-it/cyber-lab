@extends('admin.layout')

@section('title', 'Добавить тему')

@section('admin_content')
<div class="card p-4">
    <div class="fw-bold fs-4 mb-3">Новая тема</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.topics.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Название</label>
                <input class="form-control" name="title" value="{{ old('title') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Автор</label>
                <input class="form-control" name="author" value="{{ old('author') }}" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Картинка (URL)</label>
                <input class="form-control" name="image" value="{{ old('image') }}" required>
            </div>
        </div>

        <hr class="my-4 border-secondary">

        <div class="fw-bold mb-2">Награды категории</div>
        <p class="text-muted small mb-3">Необязательно. Порог — число принятых заданий в этой теме.</p>
        <div id="achievement-rows">
            @php
                $oldAchievements = old('achievements', [[]]);
                if (empty($oldAchievements)) {
                    $oldAchievements = [[]];
                }
            @endphp
            @foreach($oldAchievements as $idx => $row)
                @include('admin.topics._achievement_row', ['index' => $idx, 'row' => $row])
            @endforeach
        </div>
        <button type="button" class="btn btn-sm btn-outline-dark" id="add-achievement-row">+ Ещё награда</button>

        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-cyan">Сохранить</button>
            <a href="{{ route('admin.topics.index') }}" class="btn btn-outline-dark">Отмена</a>
        </div>
    </form>
</div>

<template id="achievement-row-template">
    @include('admin.topics._achievement_row', ['index' => '__INDEX__', 'row' => []])
</template>

<script>
(function () {
    var container = document.getElementById('achievement-rows');
    var template = document.getElementById('achievement-row-template');
    var addBtn = document.getElementById('add-achievement-row');
    if (!container || !template || !addBtn) return;

    function nextIndex() {
        return container.querySelectorAll('[data-achievement-row]').length;
    }

    addBtn.addEventListener('click', function () {
        var html = template.innerHTML.replace(/__INDEX__/g, String(nextIndex()));
        var wrap = document.createElement('div');
        wrap.innerHTML = html.trim();
        container.appendChild(wrap.firstElementChild);
    });

    container.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-remove-achievement-row]');
        if (!btn) return;
        var row = btn.closest('[data-achievement-row]');
        if (row && container.querySelectorAll('[data-achievement-row]').length > 1) {
            row.remove();
        }
    });
})();
</script>
@endsection
