@extends('layouts.app')

@section('title', $topic['title'])

@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('home') }}" class="btn btn-sm btn-cyan rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
        <span class="fs-4 mt-n1" style="color: #111;">&lsaquo;</span>
    </a>
</div>

<div class="text-center mb-5">
    <h1 class="fw-bold">{{ $topic['title'] }}</h1>
</div>

<h3 class="fw-bold mb-4">Уровни</h3>

<div class="row g-4">
    @forelse($topic['levels'] as $level)
    <div class="col-md-4">
        <div class="card h-100 p-4">
            <div class="mb-3">
                <img src="{{ $level['image'] }}" class="rounded w-100" style="height: 150px; object-fit: cover;">
            </div>
            <h5 class="fw-bold">{{ $level['title'] }}</h5>
            <div class="progress mb-2" style="height: 6px;">
                <div class="progress-bar" role="progressbar"
                     style="width: {{ $level['progress_percent'] }}%; background-color: #d8f05e;"
                     aria-valuenow="{{ $level['progress_percent'] }}" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">{{ $level['progress_current'] }}/{{ $level['progress_total'] }} заданий</span>
            </div>
            <div class="mt-auto d-flex justify-content-end">
                <a href="{{ route('level.show', $level['id']) }}" class="btn btn-cyan rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <span class="fs-4 mt-n1" style="color: #111;">&rsaquo;</span>
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-info">Для этой темы пока нет доступных уровней.</div>
    </div>
    @endforelse
</div>
@endsection
