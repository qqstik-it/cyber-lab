@extends('admin.layout')

@section('title', 'Проверка заданий')

@section('admin_content')
@php
    $statusLabels = [
        'pending' => 'Ожидают',
        'accepted' => 'Приняты',
        'rejected' => 'Отклонены',
    ];
@endphp

<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
    <div class="fw-bold fs-4">Проверка заданий</div>
    <div class="btn-group">
        <a class="btn btn-outline-dark {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('admin.submissions.index', ['status' => 'pending']) }}">Ожидают</a>
        <a class="btn btn-outline-dark {{ $status === 'accepted' ? 'active' : '' }}" href="{{ route('admin.submissions.index', ['status' => 'accepted']) }}">Приняты</a>
        <a class="btn btn-outline-dark {{ $status === 'rejected' ? 'active' : '' }}" href="{{ route('admin.submissions.index', ['status' => 'rejected']) }}">Отклонены</a>
    </div>
</div>

<div class="card p-4">
    @if($submissions->isNotEmpty())
        <ul class="list-group list-group-flush border rounded">
            @foreach($submissions as $s)
                <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                    <div>
                        <div class="fw-medium">{{ $s->user?->name }} &bull; {{ $s->task?->title }}</div>
                        <div class="text-muted small">
                            {{ $s->task?->level?->topic?->title }} / {{ $s->task?->level?->title }} &bull; Создано: {{ $s->created_at?->format('d.m.Y H:i') }}
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-muted small">{{ $statusLabels[$s->status] ?? $s->status }}</span>
                        <a class="btn btn-sm btn-outline-light" href="{{ route('admin.submissions.show', $s) }}">Проверить</a>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-muted small">Пусто.</div>
    @endif

    <div class="mt-3">
        {{ $submissions->links() }}
    </div>
</div>
@endsection

