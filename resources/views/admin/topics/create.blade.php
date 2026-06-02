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

        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-cyan">Сохранить</button>
            <a href="{{ route('admin.topics.index') }}" class="btn btn-outline-dark">Отмена</a>
        </div>
    </form>
</div>
@endsection

