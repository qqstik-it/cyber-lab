@extends('admin.layout')

@section('title', 'Добавить пользователя')

@section('admin_content')
@php
    $isAdmin = auth()->user()?->isAdmin();
@endphp
<div class="card p-4">
    <div class="fw-bold fs-4 mb-3">Новый пользователь</div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Имя</label>
                <input class="form-control" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Email</label>
                <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Пароль</label>
                <input class="form-control" type="password" name="password" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Повтор пароля</label>
                <input class="form-control" type="password" name="password_confirmation" required>
            </div>
            @if($isAdmin)
                <div class="col-md-6">
                    <label class="form-label fw-bold">Роль</label>
                    <select class="form-select" name="role" required>
                        <option value="user" @selected(old('role', 'user') === 'user')>Пользователь</option>
                        <option value="expert" @selected(old('role') === 'expert')>Эксперт</option>
                        <option value="admin" @selected(old('role') === 'admin')>Администратор</option>
                    </select>
                </div>
            @else
                <div class="col-12">
                    <div class="text-muted small">Будет создан пользователь с ролью «Пользователь».</div>
                </div>
            @endif
        </div>

        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-cyan">Сохранить</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark">Отмена</a>
        </div>
    </form>
</div>
@endsection
