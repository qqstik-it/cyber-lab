@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
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
            <h2 class="fw-bold mb-4">Регистрация в Кибер-Лаб</h2>
            
            <div class="btn-group w-100 mb-5 p-1 bg-light rounded-pill">
                <a href="{{ route('login') }}" class="btn btn-light rounded-pill">Вход</a>
                <a href="{{ route('register') }}" class="btn btn-cyan rounded-pill active">Регистрация</a>
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

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold">Имя</label>
                    <input type="text" name="name" class="form-control rounded-pill p-3 border-cyan" placeholder="Введите ваше имя" value="{{ old('name') }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">E-mail</label>
                    <input type="email" name="email" class="form-control rounded-pill p-3 border-cyan" placeholder="Введите ваш e-mail" value="{{ old('email') }}" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Пароль</label>
                    <input type="password" name="password" class="form-control rounded-pill p-3 border-cyan" placeholder="Придумайте пароль" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Подтвердите пароль</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-pill p-3 border-cyan" placeholder="Повторите пароль" required>
                </div>
                
                <button type="submit" class="btn btn-cyan w-100 rounded-pill p-3 fw-bold shadow-sm mt-4">Зарегистрироваться</button>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection
