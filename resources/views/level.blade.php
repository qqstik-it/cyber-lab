@extends('layouts.app')

@section('title', $level->title . ' - ' . $level->topic->title)

@push('page_styles')
    @php
        $levelCssPath = resource_path('css/views/level.css');
    @endphp
    @if(file_exists($levelCssPath))
        <style>{!! file_get_contents($levelCssPath) !!}</style>
    @endif
@endpush

@section('content')
@php
    $webLabEmbed = null;
    if (! empty($currentTask) && \App\Support\WebLabPageEmbed::applies($level, $currentTask)) {
        $webLabEmbed = \App\Support\WebLabPageEmbed::embedKey($currentTask);
    }
@endphp
<!-- Верхняя панель уровня -->
<div class="w-100 p-3 text-center text-white" style="background-color: #0b0b0b; border-bottom: 3px solid #d8f05e; position: sticky; top: 0; z-index: 1000;">
    <h3 class="m-0 fw-bold">{{ $level->title }}</h3>
</div>

<div class="row min-vh-100 g-0">
    <!-- Боковая панель (Подтемы и задания) -->
    <div class="col-md-3 border-end p-4 shadow-sm" style="background-color: #161b22;">
        <div class="d-flex align-items-center mb-5">
            <a href="{{ route('topic.show', $level->topic_id) }}" class="btn btn-sm btn-cyan rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                <span class="fs-4 mt-n1" style="color: #111;">&lsaquo;</span>
            </a>
        </div>
        
        @if($level->themes->isNotEmpty())
            <div class="small text-uppercase text-white-50 mb-2">Подтемы</div>
            <div class="nav flex-column nav-pills mb-4">
                @foreach($level->themes as $theme)
                    <a href="{{ route('level.show', ['id' => $level->id, 'theme_id' => $theme->id]) }}"
                       class="nav-link mb-2 p-2 rounded-3 border-0 {{ ($selectedTheme && $selectedTheme->id === $theme->id) ? 'active' : '' }}"
                       style="background-color: {{ ($selectedTheme && $selectedTheme->id === $theme->id) ? '#d8f05e' : '#1f2630' }}; color: {{ ($selectedTheme && $selectedTheme->id === $theme->id) ? '#000000' : '#ffffff' }} !important;">
                        {{ $theme->title }}
                    </a>
                @endforeach
            </div>

            <div class="small text-uppercase text-white-50 mb-2">Задания</div>
            <div class="nav flex-column nav-pills">
                @forelse($tasks as $task)
                    @php
                        $isActive = $currentTask && $currentTask->id == $task->id;
                        $status = $task->submissions->first()?->status;
                        
                        if ($status === 'accepted') {
                            $bgColor = '#2ea043'; // bright green
                            $textColor = '#ffffff';
                        } elseif ($status === 'rejected') {
                            $bgColor = '#da3633'; // red
                            $textColor = '#ffffff';
                        } elseif ($status === 'pending') {
                            $bgColor = '#ffc107'; // yellow
                            $textColor = '#000000';
                        } elseif ($isActive) {
                            $bgColor = '#d8f05e'; // lime
                            $textColor = '#000000';
                        } else {
                            $bgColor = '#1f2630'; // dark inactive
                            $textColor = '#ffffff';
                        }
                    @endphp
                    <a href="{{ route('level.show', ['id' => $level->id, 'theme_id' => $selectedTheme->id, 'task_id' => $task->id]) }}"
                       class="nav-link mb-3 p-3 rounded-4 border-0 {{ $isActive ? 'shadow-sm fw-bold' : '' }}"
                       style="background-color: {{ $bgColor }}; color: {{ $textColor }} !important; border: 1px solid rgba(255,255,255,0.06);">
                        <div class="d-flex align-items-center">
                            <div class="me-3 fs-5">@if($loop->index == 0) 📔 @else 📖 @endif</div>
                            <div class="small fw-medium" style="white-space: normal; line-height: 1.2;">{{ $task->title }}</div>
                        </div>
                    </a>
                @empty
                    <div class="text-white-50 small">В этой подтеме пока нет заданий.</div>
                @endforelse
            </div>
        @else
            <div class="small text-uppercase text-white-50 mb-2">Задания</div>
            <div class="nav flex-column nav-pills">
                @forelse($tasks as $task)
                    @php
                        $isActive = $currentTask && $currentTask->id == $task->id;
                        $status = $task->submissions->first()?->status;
                        
                        if ($status === 'accepted') {
                            $bgColor = '#2ea043'; // bright green
                            $textColor = '#ffffff';
                        } elseif ($status === 'rejected') {
                            $bgColor = '#da3633'; // red
                            $textColor = '#ffffff';
                        } elseif ($status === 'pending') {
                            $bgColor = '#ffc107'; // yellow
                            $textColor = '#000000';
                        } elseif ($isActive) {
                            $bgColor = '#d8f05e'; // lime
                            $textColor = '#000000';
                        } else {
                            $bgColor = '#1f2630'; // dark inactive
                            $textColor = '#ffffff';
                        }
                    @endphp
                    <a href="{{ route('level.show', ['id' => $level->id, 'task_id' => $task->id]) }}"
                       class="nav-link mb-3 p-3 rounded-4 border-0 {{ $isActive ? 'shadow-sm fw-bold' : '' }}"
                       style="background-color: {{ $bgColor }}; color: {{ $textColor }} !important; border: 1px solid rgba(255,255,255,0.06);">
                        <div class="d-flex align-items-center">
                            <div class="me-3 fs-5">@if($loop->index == 0) 📔 @else 📖 @endif</div>
                            <div class="small fw-medium" style="white-space: normal; line-height: 1.2;">{{ $task->title }}</div>
                        </div>
                    </a>
                @empty
                    <div class="text-white-50 small">Для уровня пока нет заданий.</div>
                @endforelse
            </div>
        @endif

        @if($webLabEmbed)
            @include('level._web_lab_page_embed', ['webLabEmbed' => $webLabEmbed, 'embedSlot' => 'sidebar'])
        @endif
    </div>

    <!-- Основной контент задания -->
    <div class="col-md-9 p-5" style="background-color: #0d1117;">
        @if($currentTask)
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold m-0">{{ $currentTask->title }}</h2>
        </div>

        @if(session('submission_saved'))
            <div class="alert alert-success">Ответ отправлен.</div>
        @endif

        @if($submission)
            @php
                $badge = $submission->status === 'pending' ? 'warning' : ($submission->status === 'accepted' ? 'success' : 'danger');
                $label = $submission->status === 'pending' ? 'Ожидает проверки' : ($submission->status === 'accepted' ? 'Принято' : 'Отклонено');
            @endphp
            <div class="alert alert-{{ $badge }}">
                <div class="fw-bold">Статус: {{ $label }}</div>
                @if($submission->feedback)
                    <div class="mt-2"><span class="fw-bold">Комментарий:</span> {{ $submission->feedback }}</div>
                @endif
            </div>
        @endif

        <div class="card p-5 shadow-sm rounded-4 mb-5">
            <div class="task-content fs-5 line-height-lg">
                {!! $currentTask->content !!}
            </div>
        </div>

        @if($webLabEmbed)
            @include('level._web_lab_page_embed', ['webLabEmbed' => $webLabEmbed, 'embedSlot' => 'main'])
        @endif

        <div class="mt-5">
            @if($webLabEmbed)
                @include('level._web_lab_page_embed', ['webLabEmbed' => $webLabEmbed, 'embedSlot' => 'form'])
            @endif
            <form method="POST" action="{{ route('tasks.submit', $currentTask) }}">
                @csrf
                <div class="d-flex justify-content-center">
                    <div style="max-width: 700px; width: 100%;">
                        <label class="form-label fw-bold">Ответ</label>
                        <div class="input-group">
                            <input
                                type="text"
                                name="answer"
                                class="form-control rounded-pill rounded-end-0 p-3 border-0 shadow-sm"
                                placeholder="{{ $currentTask->correct_answer ? 'Введите ответ (автопроверка)' : 'Опишите решение (пойдёт на ручную проверку)' }}"
                                value="{{ old('answer', $submission?->answer) }}"
                            >
                            <button class="btn btn-cyan rounded-pill rounded-start-0 px-5 fw-bold shadow-sm" type="submit">
                                Отправить
                            </button>
                        </div>
                        @if($currentTask->correct_answer)
                            <div class="text-muted small mt-2">Для этого задания ответ проверяется автоматически.</div>
                        @else
                            <div class="text-muted small mt-2">Для этого задания требуется ручная проверка администратором.</div>
                        @endif
                        @error('answer')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>
        @else
        <div class="text-center py-5">
            <h3 class="text-muted">Задание не найдено</h3>
        </div>
        @endif
    </div>
</div>

@endsection
