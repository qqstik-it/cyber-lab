@extends('layouts.app')

@section('title', 'Темы')

@section('content')
<h2 class="fw-bold mb-4">Темы</h2>
<div class="row g-4">
    @foreach($topics as $topic)
    <div class="col-md-6 col-lg-3">
        <div class="card h-100 overflow-hidden">
            <img src="{{ $topic['image'] }}" class="card-img-top" alt="{{ $topic['title'] }}" style="height: 180px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title fw-bold">{{ $topic['title'] }}</h5>
                <div class="progress mb-2" style="height: 6px;">
                    <div class="progress-bar" role="progressbar" 
                         style="width: {{ $topic['progress_total'] > 0 ? ($topic['progress_current'] / $topic['progress_total'] * 100) : 0 }}%; background-color: #d8f05e;">
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">{{ $topic['progress_current'] }}/{{ $topic['progress_total'] }}</span>
                    <a href="{{ route('topic.show', $topic['id']) }}" class="btn btn-sm btn-cyan rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <span class="fs-4 mt-n1" style="color: #111;">&rsaquo;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
