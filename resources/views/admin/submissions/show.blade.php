@extends('admin.layout')

@section('title', 'Сдача задания')

@section('admin_content')
@php
    $badge = $submission->status === 'pending' ? 'warning' : ($submission->status === 'accepted' ? 'success' : 'danger');
    $statusLabel = [
        'pending' => 'Ожидает',
        'accepted' => 'Принято',
        'rejected' => 'Отклонено',
    ][$submission->status] ?? $submission->status;
@endphp

<div class="d-flex justify-content-between align-items-start gap-3 mb-3">
    <div>
        <div class="fw-bold fs-4 mb-1">Сдача #{{ $submission->id }}</div>
        <div class="text-muted">
            {{ $submission->task?->level?->topic?->title }} /
            {{ $submission->task?->level?->title }} /
            {{ $submission->task?->title }}
        </div>
        <div class="mt-2">
            <span class="badge bg-{{ $badge }}">{{ $statusLabel }}</span>
            <span class="text-muted ms-2">От: <span class="fw-medium">{{ $submission->user?->name }}</span></span>
        </div>
    </div>
    <div class="text-end">
        <a href="{{ route('admin.submissions.index', ['status' => $submission->status]) }}" class="btn btn-outline-dark">Назад</a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('feedback_saved'))
    <div class="alert alert-success mb-3">Комментарий сохранён. Пользователь увидит его на странице задания.</div>
@endif

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card p-4 mb-3">
            <div class="fw-bold mb-2">Ответ пользователя</div>
            <div class="p-3 rounded admin-light-panel">{{ $submission->answer }}</div>

            @if ($submission->status === 'pending')
                <div class="mt-4">
                    <label class="form-label fw-bold mb-1" for="submission_review_comment">Комментарий</label>
                    <textarea
                        class="form-control admin-comment-field"
                        rows="3"
                        id="submission_review_comment"
                        placeholder="Комментарий к проверке (необязательно)"
                    >{{ old('feedback', $submission->feedback) }}</textarea>
                    <div class="form-text text-muted">Комментарий будет сохранён при принятии или отклонении сдачи.</div>
                </div>
            @else
                @if ($submission->feedback)
                    <div class="mt-4">
                        <div class="fw-bold mb-2">Текущий комментарий для пользователя</div>
                        <div class="p-3 rounded admin-light-panel">{{ $submission->feedback }}</div>
                    </div>
                @endif

                <div class="mt-4">
                    <div class="fw-bold mb-2">Комментарий для пользователя</div>
                    <p class="text-muted small mb-2">Текст ниже показывается у обучающегося на странице задания вместе со статусом сдачи.</p>
                    <form method="POST" action="{{ route('admin.submissions.feedback', $submission) }}">
                        @csrf
                        <textarea
                            class="form-control admin-comment-field mb-3"
                            rows="4"
                            name="feedback"
                            placeholder="Напишите комментарий…"
                        >{{ old('feedback', $submission->feedback) }}</textarea>
                        <button type="submit" class="btn btn-cyan">Отправить комментарий</button>
                    </form>
                </div>
            @endif
        </div>

        @if($submission->task?->correct_answer)
            <div class="card p-4">
                <div class="fw-bold mb-2">Эталонный ответ</div>
                <div class="p-3 rounded admin-light-panel">{{ $submission->task->correct_answer }}</div>
            </div>
        @endif
    </div>

    <div class="col-lg-5">
        @if ($submission->status === 'pending')
            <div class="card p-4 mb-3">
                <div class="fw-bold mb-3">Действия</div>

                <form method="POST" action="{{ route('admin.submissions.accept', $submission) }}" id="form-accept-submission" class="mb-3">
                    @csrf
                    <input type="hidden" name="feedback" value="">
                    <button type="submit" class="btn btn-success w-100">Принять</button>
                </form>

                <form method="POST" action="{{ route('admin.submissions.reject', $submission) }}" id="form-reject-submission">
                    @csrf
                    <input type="hidden" name="feedback" value="">
                    <button type="submit" class="btn btn-danger w-100">Отклонить</button>
                </form>
            </div>
        @endif

        <div class="card p-4">
            <div class="fw-bold mb-2">Инфо</div>
            <div class="text-muted small">Создано: {{ $submission->created_at?->format('d.m.Y H:i') }}</div>
            <div class="text-muted small">Обновлено: {{ $submission->updated_at?->format('d.m.Y H:i') }}</div>
            <div class="text-muted small">
                Проверил: {{ $submission->reviewer?->name ?? '—' }}
            </div>
            <div class="text-muted small">
                Когда: {{ $submission->reviewed_at?->format('d.m.Y H:i') ?? '—' }}
            </div>
        </div>
    </div>
</div>

@if ($submission->status === 'pending')
<script>
    (function () {
        var comment = document.getElementById('submission_review_comment');
        var acceptForm = document.getElementById('form-accept-submission');
        var rejectForm = document.getElementById('form-reject-submission');
        function syncFeedback(form) {
            if (!comment || !form) return;
            var hidden = form.querySelector('input[name="feedback"]');
            if (hidden) hidden.value = comment.value;
        }
        if (acceptForm) acceptForm.addEventListener('submit', function () { syncFeedback(acceptForm); });
        if (rejectForm) rejectForm.addEventListener('submit', function () { syncFeedback(rejectForm); });
    })();
</script>
@endif
@endsection
