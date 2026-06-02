@extends('admin.layout')

@section('title', 'Админка')

@section('admin_content')
@php
    $statusLabels = [
        'pending' => 'Ожидает',
        'accepted' => 'Принято',
        'rejected' => 'Отклонено',
    ];
@endphp

<div class="row g-3">
    <div class="col-md-4">
        <div class="card p-4">
            <div class="fw-bold mb-2">Ожидают проверки</div>
            <div class="display-6 fw-bold text-dark">{{ $pendingCount }}</div>
            <div class="mt-3">
                <a href="{{ route('admin.submissions.index', ['status' => 'pending']) }}" class="btn btn-cyan w-100">Перейти к проверке</a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card p-4">
            <div class="fw-bold mb-3">Последние сдачи</div>
            @if($recent->isNotEmpty())
                <ul class="list-group list-group-flush border rounded">
                    @foreach($recent as $s)
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent text-reset border-secondary-subtle">
                            <div>
                                <div class="fw-medium">{{ $loop->iteration }}. {{ $s->user?->name }} &bull; {{ $s->task?->title }}</div>
                                <div class="text-muted small">
                                    {{ $s->task?->level?->topic?->title }} / {{ $s->task?->level?->title }}
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $badge = $s->status === 'pending' ? 'warning' : ($s->status === 'accepted' ? 'success' : 'danger');
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ $statusLabels[$s->status] ?? $s->status }}</span>
                                <a class="btn btn-sm btn-outline-light" href="{{ route('admin.submissions.show', $s) }}">Открыть</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-muted small">Пока нет сдач.</div>
            @endif
        </div>
    </div>
</div>
@endsection

