@extends('admin.layout')

@section('title', 'Редактировать тему')

@section('admin_content')
@php($isAdmin = auth()->user()?->isAdmin())
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.topics.index') }}" class="btn btn-sm btn-cyan rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
        <span class="fs-4 mt-n1" style="color: #111;">&lsaquo;</span>
    </a>
    <span class="text-muted small">Назад к списку тем</span>
</div>

<div class="mb-4 ms-1">
    <h2 class="fw-bold mb-1">{{ $topic->title }}</h2>
    <div class="text-muted">Тема #{{ $topic->id }} &bull; Автор: {{ $topic->author }}</div>
</div>

<div class="row g-3">
    <div class="col-lg-10 col-xl-8">
        @forelse($topic->levels as $lvl)
            <div class="card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <div class="fw-bold">{{ $lvl->title }}</div>
                    </div>
                    <div class="text-end">
                        <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.levels.edit', $lvl) }}">Открыть</a>
                        @if($isAdmin)
                            <form action="{{ route('admin.levels.destroy', $lvl) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить уровень?')">Удалить</button>
                            </form>
                        @endif
                    </div>
                </div>

                <hr>

                @if($lvl->themes->isNotEmpty())
                    @foreach($lvl->themes as $th)
                        <div class="mb-3">
                            <div class="small text-uppercase text-muted mb-1">Подтема</div>
                            <div class="fw-medium mb-2">{{ $th->title }}</div>
                            @if($th->tasks->isNotEmpty())
                                <ul class="list-group list-group-flush border rounded">
                                    @foreach($th->tasks as $tk)
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                                            <div>
                                                <div class="fw-medium">{{ $loop->iteration }}. {{ $tk->title }}</div>
                                                <div class="text-muted small">
                                                    {{ $tk->correct_answer ? 'Автопроверка' : 'Ручная проверка' }}
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                @if($isAdmin)
                                                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.tasks.edit', $tk) }}">Ред.</a>
                                                    <form action="{{ route('admin.tasks.destroy', $tk) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить задание?')">×</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="text-muted small">В этой подтеме пока нет заданий.</div>
                            @endif
                        </div>
                    @endforeach
                @endif

                @php
                    $orphanTasks = $lvl->tasks->whereNull('level_theme_id');
                @endphp
                @if($orphanTasks->isNotEmpty())
                    <div class="mb-0">
                        <div class="small text-uppercase text-muted mb-1">Задания без подтемы</div>
                        <ul class="list-group list-group-flush border rounded">
                            @foreach($orphanTasks as $tk)
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                                    <div>
                                        <div class="fw-medium">{{ $loop->iteration }}. {{ $tk->title }}</div>
                                        <div class="text-muted small">
                                            {{ $tk->correct_answer ? 'Автопроверка' : 'Ручная проверка' }}
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($isAdmin)
                                            <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.tasks.edit', $tk) }}">Ред.</a>
                                            <form action="{{ route('admin.tasks.destroy', $tk) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить задание?')">×</button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($lvl->themes->isEmpty() && $orphanTasks->isEmpty())
                    <div class="text-muted">Пока нет подтем и заданий для этого уровня.</div>
                @endif
            </div>
        @empty
            <div class="card p-4">
                <div class="text-muted">Для этой темы нет уровней.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
