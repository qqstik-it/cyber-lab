@extends('admin.layout')

@section('title', 'Редактировать задание')

@section('admin_content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.levels.edit', $task->level) }}" class="btn btn-sm btn-cyan rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
        <span class="fs-4 mt-n1" style="color: #111;">&lsaquo;</span>
    </a>
    <span class="text-muted small">Назад к уровню</span>
</div>

<div class="card p-4">
    <div class="fw-bold fs-4 mb-1">Задание #{{ $task->id }}</div>
    <div class="text-muted mb-3">
        {{ $task->level->topic->title }} / {{ $task->level->title }}
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.tasks.update', $task) }}">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-bold">Подтема</label>
                <select class="form-select admin-comment-field" name="level_theme_id">
                    <option value="">— без подтемы —</option>
                    @foreach($task->level->themes as $theme)
                        <option value="{{ $theme->id }}" @selected((string) old('level_theme_id', $task->level_theme_id) === (string) $theme->id)>
                            {{ $theme->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-bold">Название</label>
                <input class="form-control admin-comment-field" name="title" value="{{ old('title', $task->title) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Порядок</label>
                <input class="form-control admin-comment-field" type="number" min="0" name="order" value="{{ old('order', $task->order) }}">
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Формулировка</label>
                <textarea class="form-control admin-comment-field" rows="10" name="content" required>{{ old('content', $task->content) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Ответ</label>
                <input class="form-control admin-comment-field" name="correct_answer" value="{{ old('correct_answer', $task->correct_answer) }}">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-cyan">Сохранить</button>
        </div>
    </form>
</div>
@endsection

@push('page_scripts')
<style>
    .note-editor.note-frame .note-editing-area .note-editable {
        background-color: #ffffff !important;
        color: #000000 !important;
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    $('textarea[name="content"]').summernote({
        height: 300,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>
@endpush
