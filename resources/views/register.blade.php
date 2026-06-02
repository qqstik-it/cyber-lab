@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
<div class="row min-vh-100 align-items-center">
    <div class="col-md-6 d-none d-md-block p-5">
        <div class="card bg-dark text-white p-5 h-100 overflow-hidden" style="background-image: url('https://img.freepik.com/free-vector/abstract-digital-technology-background-with-mesh-lines_1017-25121.jpg'); background-size: cover; border-radius: 30px;">
            <div class="position-relative" style="z-index: 2;">
                <h1 class="fw-bold display-4 mb-4">Кибер-Лаб</h1>
                <p class="lead opacity-75">Добро пожаловать в современную платформу для изучения информационной безопасности.</p>
            </div>
            <div class="mt-auto text-center" style="z-index: 2;">
                <img src="https://img.freepik.com/free-vector/digital-shield-security-concept-protection-antivirus_1017-29538.jpg" class="img-fluid rounded-4 shadow-lg w-75">
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
        </div>
    </div>
    <div class="col-md-6 p-md-5">
        <div class="card p-5 border-0 shadow-none bg-transparent">
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
@endsection
