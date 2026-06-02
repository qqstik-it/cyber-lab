@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
<div class="row mb-5">
    <div class="col-12">
        <div class="card p-4 overflow-hidden position-relative" style="background-image: url('https://img.freepik.com/free-vector/abstract-background-design-with-shining-lines_1048-12499.jpg'); background-size: cover; height: 180px;">
            <div class="d-flex align-items-center h-100 px-4 position-relative" style="z-index: 2; background: rgba(13, 17, 23, 0.78); border-radius: 10px; width: fit-content;">
                <img src="{{ $user['avatar'] }}" class="rounded-circle border border-white border-4 me-4" width="100" height="100">
                <div>
                    <h3 class="fw-bold mb-1">{{ $user['name'] }}</h3>
                    <span class="badge bg-cyan">{{ $user['role'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <button class="list-group-item list-group-item-action active border-0" style="background-color: #d8f05e; color: #111;">Прогресс</button>
            <button class="list-group-item list-group-item-action border-0">История</button>
            <button class="list-group-item list-group-item-action border-0">Рекомендации</button>
            <button class="list-group-item list-group-item-action border-0">Навигация</button>
        </div>
    </div>
    <div class="col-md-9">
        @if(isset($recentSubmissions) && $recentSubmissions->isNotEmpty())
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-4">Уведомления (проверенные задания)</h5>
                <ul class="list-group list-group-flush border rounded">
                    @foreach($recentSubmissions as $submission)
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                            <div>
                                <div class="fw-medium">
                                    {{ $submission->task?->title }}
                                </div>
                                <div class="text-muted small">
                                    {{ $submission->task?->level?->topic?->title }} / {{ $submission->task?->level?->title }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                @if($submission->status === 'accepted')
                                    <span class="badge bg-success">Задание проверено (Принято)</span>
                                @else
                                    <span class="badge bg-danger">Задание проверено (Отклонено)</span>
                                @endif
                                <a href="{{ route('level.show', ['id' => $submission->task?->level_id, 'task_id' => $submission->task_id]) }}" class="btn btn-sm btn-outline-light">Перейти к заданию</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card p-4">
            <h5 class="fw-bold mb-4">Ваш прогресс</h5>
            <div class="row g-4">
                @foreach($user['stats'] as $stat)
                <div class="col-md-12">
                    <div class="d-flex align-items-center mb-2">
                        <div class="me-3 fs-3" style="width: 40px; text-align: center;">🛡️</div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold">{{ $stat['title'] }}</span>
                                <span class="text-muted">{{ $stat['progress'] }}%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ $stat['color'] }}" role="progressbar" style="width: {{ $stat['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
