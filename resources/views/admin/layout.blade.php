@extends('layouts.app')

@push('page_styles')
<style>
    /* Светлые блоки внутри тёмных карточек: явный тёмный текст */
    .admin-light-panel {
        background: #f1f5f9 !important;
        color: #0f172a !important;
        white-space: pre-wrap;
    }
    textarea.admin-comment-field,
    .admin-comment-field.form-control,
    select.admin-comment-field.form-select,
    .admin-comment-field.form-select {
        background: #fff !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }

    /* Кнопки outline на тёмном фоне админки — читаемые границы и текст */
    .admin-shell .btn.btn-outline-dark {
        color: #e6edf3 !important;
        border-color: #8b98a8 !important;
        background-color: rgba(255, 255, 255, 0.06);
    }
    .admin-shell .btn.btn-outline-dark:hover,
    .admin-shell .btn.btn-outline-dark:focus {
        color: #0f172a !important;
        background-color: #d8f05e !important;
        border-color: #d8f05e !important;
    }
    .admin-shell .btn-group > .btn.btn-outline-dark.active,
    .admin-shell .btn-group > .btn.btn-outline-dark:active {
        color: #0f172a !important;
        background-color: #d8f05e !important;
        border-color: #d8f05e !important;
    }
</style>
@endpush

@section('content')
@php
    $panelUser = auth()->user();
    $adminNav = match (true) {
        request()->routeIs('admin.dashboard') => 'dashboard',
        request()->routeIs('admin.topics.*', 'admin.levels.*', 'admin.tasks.*') => 'topics',
        request()->routeIs('admin.users.*') => 'users',
        request()->routeIs('admin.submissions.*') => 'submissions',
        request()->routeIs('admin.achievements.*') => 'achievements',
        default => null,
    };
    $adminNavBtn = fn (string $key) => $adminNav === $key ? 'btn btn-cyan' : 'btn btn-outline-dark';
@endphp
<div class="container py-4 admin-shell">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <div class="fw-bold fs-3 mb-1">{{ $panelUser?->isAdmin() ? 'Админ‑панель' : 'Панель эксперта' }}</div>
            <div class="text-muted">
                {{ $panelUser?->isAdmin() ? 'Управление контентом и проверка заданий' : 'Проверка сдач и добавление заданий' }}
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.dashboard') }}" class="{{ $adminNavBtn('dashboard') }}">Панель</a>
            @if($panelUser?->canManageTasks())
                <a href="{{ route('admin.topics.index') }}" class="{{ $adminNavBtn('topics') }}">Темы</a>
            @endif
            @if($panelUser?->canManageUsers())
                <a href="{{ route('admin.users.index') }}" class="{{ $adminNavBtn('users') }}">Пользователи</a>
            @endif
            @if($panelUser?->isAdmin())
                <a href="{{ route('admin.achievements.index') }}" class="{{ $adminNavBtn('achievements') }}">Награды</a>
            @endif
            <a href="{{ route('admin.submissions.index') }}" class="{{ $adminNavBtn('submissions') }}">Проверки</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('admin_content')
</div>
@endsection
