@extends('admin.layout')

@section('title', 'Добавить уровень')

@section('admin_content')
<div class="card p-4">
    <div class="fw-bold fs-4 mb-1">Новый уровень</div>
    <div class="text-muted mb-3">Тема: <span class="fw-medium">{{ $topic->title }}</span></div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.levels.store', $topic) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label fw-bold">Название</label>
            <input class="form-control" name="title" value="{{ old('title') }}" required>
        </div>
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-cyan">Сохранить</button>
            <a href="{{ route('admin.topics.edit', $topic) }}" class="btn btn-outline-dark">Назад</a>
        </div>
    </form>
</div>
@endsection

