@extends('layouts.app')

@section('title', 'Вход')

@section('content')
<a href="{{ route('landing') }}" class="auth-back-link" aria-label="На главную">←</a>
<div class="auth-page min-vh-100 d-flex align-items-center">
<div class="container-fluid">
<div class="row align-items-center">
    <div class="col-md-6 d-none d-md-block p-5">
        <div class="auth-panel text-white p-5 h-100 d-flex flex-column">
            
            <div class="mt-auto text-center auth-panel-dino">
                <img src="{{ asset('img/dino.png') }}" alt="Кибер-Лаб динозавр" class="img-fluid">
            </div>
        </div>
    </div>
    <div class="col-md-6 p-md-5">
        <div class="card auth-form p-5 border-0 shadow-none">
            <h2 class="fw-bold mb-4">Приветствуем в Кибер-Лаб</h2>
            
            <div class="btn-group w-100 mb-5 p-1 bg-light rounded-pill">
                <a href="{{ route('login') }}" class="btn btn-cyan rounded-pill active">Вход</a>
                <a href="{{ route('register') }}" class="btn btn-light rounded-pill">Регистрация</a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login', [], false) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold">E-mail</label>
                    <input type="email" name="email" class="form-control rounded-pill p-3 border-cyan" placeholder="Введите ваш e-mail" value="{{ old('email') }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Пароль</label>
                    <div class="input-group">
                        <input type="password" name="password" id="login-password" class="form-control rounded-pill rounded-end-0 p-3 border-cyan border-end-0" placeholder="Введите ваш пароль" required>
                        <button type="button" class="input-group-text rounded-pill rounded-start-0 bg-transparent border-cyan border-start-0 px-3 password-toggle" aria-label="Показать пароль">👁️</button>
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-5">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Запомнить меня</label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-cyan w-100 rounded-pill p-3 fw-bold shadow-sm">Войти</button>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection

@push('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.querySelector('.password-toggle');
    var input = document.getElementById('login-password');
    if (!toggle || !input) return;

    function showPassword() {
        input.type = 'text';
    }

    function hidePassword() {
        input.type = 'password';
    }

    toggle.addEventListener('mouseenter', showPassword);
    toggle.addEventListener('mouseleave', hidePassword);
    toggle.addEventListener('mousedown', function (e) {
        e.preventDefault();
        showPassword();
    });
    toggle.addEventListener('mouseup', hidePassword);
    toggle.addEventListener('touchstart', function (e) {
        e.preventDefault();
        showPassword();
    }, { passive: false });
    toggle.addEventListener('touchend', hidePassword);
});
</script>
@endpush
