@extends('admin.layout')

@section('title', 'Редактировать уровень')

@section('admin_content')
@php
    $isAdmin = auth()->user()?->isAdmin();
@endphp
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.topics.edit', $level->topic) }}" class="btn btn-sm btn-cyan rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
        <span class="fs-4 mt-n1" style="color: #111;">&lsaquo;</span>
    </a>
    <span class="text-muted small">Назад к теме «{{ $level->topic->title }}»</span>
</div>

<div class="mb-4 ms-1">
    <h2 class="fw-bold mb-1">{{ $level->title }}</h2>
    <div class="text-muted">{{ $level->topic->title }} &bull; Уровень #{{ $level->id }}</div>
</div>

<div class="row g-3">
    <div class="col-lg-10 col-xl-8">
        @if($level->themes->isNotEmpty())
            @foreach($level->themes as $th)
                <div class="card p-4 mb-3">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <div class="small text-uppercase text-muted mb-1">Подтема</div>
                            <div class="fw-bold">{{ $th->title }}</div>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('admin.tasks.create', ['level' => $level, 'theme_id' => $th->id]) }}" class="btn btn-sm btn-cyan">Добавить задание</a>
                        </div>
                    </div>
                    @if($th->tasks->isNotEmpty())
                        <ul class="list-group list-group-flush border rounded">
                            @foreach($th->tasks as $t)
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                                    <div>
                                        <div class="fw-medium">{{ $loop->iteration }}. {{ $t->title }}</div>
                                        <div class="text-muted small">
                                            {{ $t->correct_answer ? 'Автопроверка' : 'Ручная проверка' }}
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        @if($isAdmin)
                                            <a class="btn btn-sm btn-outline-light" href="{{ route('admin.tasks.edit', $t) }}">Редактировать</a>
                                            <form action="{{ route('admin.tasks.destroy', $t) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить задание?')">Удалить</button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted small">Нет заданий в этой подтеме.</div>
                    @endif
                </div>
            @endforeach
        @endif

        @php
            $orphanTasks = $level->tasks->whereNull('level_theme_id');
        @endphp
        @if($orphanTasks->isNotEmpty())
            <div class="card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                    <div>
                        <div class="small text-uppercase text-muted mb-1">Задания без подтемы</div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('admin.tasks.create', $level) }}" class="btn btn-sm btn-cyan">Добавить задание</a>
                    </div>
                </div>
                <ul class="list-group list-group-flush border rounded">
                    @foreach($orphanTasks as $t)
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                            <div>
                                <div class="fw-medium">{{ $loop->iteration }}. {{ $t->title }}</div>
                                <div class="text-muted small">
                                    {{ $t->correct_answer ? 'Автопроверка' : 'Ручная проверка' }}
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                @if($isAdmin)
                                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.tasks.edit', $t) }}">Редактировать</a>
                                    <form action="{{ route('admin.tasks.destroy', $t) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить задание?')">Удалить</button>
                                    </form>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($level->themes->isEmpty() && $orphanTasks->isEmpty())
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted">Пока нет подтем и заданий. Добавьте задание и укажите подтему в форме.</div>
                    <a href="{{ route('admin.tasks.create', $level) }}" class="btn btn-sm btn-cyan">Добавить задание</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
