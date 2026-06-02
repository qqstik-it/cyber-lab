@extends('admin.layout')

@section('title', 'Темы (админка)')

@section('admin_content')
@php
    $isAdmin = auth()->user()?->isAdmin();
@endphp
<div class="d-flex justify-content-between align-items-center mb-3">
    <div class="fw-bold fs-4">Темы</div>
    @if($isAdmin)
        <a href="{{ route('admin.topics.create') }}" class="btn btn-cyan">Добавить тему</a>
    @endif
</div>

<div class="card p-4">
    @if($topics->isNotEmpty())
        <ul class="list-group list-group-flush border rounded">
            @foreach($topics as $t)
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                    <div>
                        <div class="fw-medium">#{{ $t->id }} {{ $t->title }}</div>
                        <div class="text-muted small">
                            Автор: {{ $t->author }} &bull; Уровней: {{ $t->levels_count }}
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.topics.edit', $t) }}" class="btn btn-sm btn-outline-light">Открыть</a>
                        @if($isAdmin)
                            <form action="{{ route('admin.topics.destroy', $t) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить тему?')">Удалить</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-muted small">Пока нет тем.</div>
    @endif
</div>
@endsection

