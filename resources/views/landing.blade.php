@extends('layouts.app')

@section('title', 'Главный экран')

@push('page_styles')
    @php($landingCssPath = resource_path('css/views/landing.css'))
    @if (file_exists($landingCssPath))
        <style>{!! file_get_contents($landingCssPath) !!}</style>
    @endif
@endpush

@section('content')
<div class="landing-wrap">
    <div class="topbar">
        <div class="container py-2 d-flex align-items-center justify-content-between">
        <a href="#top" class="text-decoration-none d-inline-flex align-items-center">
    <img src="{{ asset('img/logo.png') }}" alt="Логотип" style="height: 100px; width: auto;"></a>            <div class="d-none d-md-flex gap-3">
                <a href="#menu" class="nav-link-soft">Меню</a>
                <a href="#about" class="nav-link-soft">О платформе</a>
                <a href="#why" class="nav-link-soft">Зачем</a>
                <a href="#experts" class="nav-link-soft">Топ‑специалисты</a>
                <a href="#rewards" class="nav-link-soft">Награды</a>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('login') }}" class="pill-btn">Войти</a>
                <a href="{{ route('register') }}" class="pill-btn accent">Начать</a>
            </div>
        </div>
    </div>

    <section class="hero reveal in" id="top">
        <div class="container hero-inner position-relative h-100">
            <div class="hero-brand">
                <h1 class="hero-title">КИБЕР-ЛАБ</h1>
                <div class="hero-dino">
                    <img src="{{ asset('img/dino.png') }}" alt="Кибер-Лаб динозавр">
                </div>
            </div>
        </div>

        <div class="hero-register">
            <a href="{{ route('register') }}" class="pill-btn accent fs-4 px-5 py-3 shadow">Регистрация</a>
        </div>

        <div class="section-line" aria-hidden="true">
            <img src="{{ asset('img/line.png') }}" alt="">
        </div>
    </section>

    <section class="about-section reveal" id="about">
        <div class="container">
            <div class="about-head text-center">
                <h2 class="about-title">Что такое Кибер-Лаб</h2>
                <p class="about-lead">
                    Кибер-Лаб — учебная платформа по информационной безопасности. <br> Решай задачи. Прокачивайся. Становись экспертом.
                </p>
            </div>
            <div class="row g-4 g-lg-5 about-features">
                <div class="col-md-4">
                    <div class="about-feature">
                        <span class="about-corner about-corner-tl" aria-hidden="true"></span>
                        <span class="about-corner about-corner-tr" aria-hidden="true"></span>
                        <span class="about-corner about-corner-bl" aria-hidden="true"></span>
                        <span class="about-corner about-corner-br" aria-hidden="true"></span>
                        <h3 class="about-feature-title"><span class="about-accent">Практика</span> в деле</h3>
                        <p class="about-feature-desc">Задания по криптографии, web, сетям и аутентификации — не теория в вакууме, а навыки, которые можно применить</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-feature">
                        <span class="about-corner about-corner-tl" aria-hidden="true"></span>
                        <span class="about-corner about-corner-tr" aria-hidden="true"></span>
                        <span class="about-corner about-corner-bl" aria-hidden="true"></span>
                        <span class="about-corner about-corner-br" aria-hidden="true"></span>
                        <h3 class="about-feature-title">Путь <span class="about-accent">по уровням</span></h3>
                        <p class="about-feature-desc">Темы разбиты на уровни сложности — от базовых задач к более продвинутым, с понятным прогрессом</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-feature">
                        <span class="about-corner about-corner-tl" aria-hidden="true"></span>
                        <span class="about-corner about-corner-tr" aria-hidden="true"></span>
                        <span class="about-corner about-corner-bl" aria-hidden="true"></span>
                        <span class="about-corner about-corner-br" aria-hidden="true"></span>
                        <h3 class="about-feature-title"><span class="about-accent">Проверка</span> ответов</h3>
                        <p class="about-feature-desc">Часть заданий проверяется автоматически, остальные — экспертами с комментарием и статусом в личном кабинете</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="menu-section section reveal" id="menu">
        <div class="section-line section-line-top" aria-hidden="true">
            <img src="{{ asset('img/line.png') }}" alt="">
        </div>
        <div class="container">
            <h3 class="menu-section-title mb-3">Меню направлений ИБ</h3>
            <div class="row g-3">
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="service-card dark">
                        <div class="icon-dot mb-3">🔐</div>
                        <h5 class="fw-bold">Криптография</h5>
                        <p class="mb-0 text-white-50">Шифрование, ключи, цифровая подпись и защита данных.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="service-card accent">
                        <div class="icon-dot mb-3">🧩</div>
                        <h5 class="fw-bold">WEB</h5>
                        <p class="mb-0">Теги, атрибуты, CSS-селекторы и безопасная структура интерфейсов.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="service-card">
                        <div class="icon-dot mb-3">🌐</div>
                        <h5 class="fw-bold">Сетевой трафик</h5>
                        <p class="mb-0 text-muted">TCP/IP, анализ пакетов, сетевые сценарии и мониторинг.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="service-card accent">
                        <div class="icon-dot mb-3">🧷</div>
                        <h5 class="fw-bold">Аутентификация</h5>
                        <p class="mb-0">Типы аутентификации, 2FA и современные методы защиты входа.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="lime-section">
        <div class="section-line section-line-top" aria-hidden="true">
            <img src="{{ asset('img/line.png') }}" alt="">
        </div>
        <div class="container">
        <!-- <section class="section" id="audience">
            <div class="highlight reveal">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <h3 class="fw-bold mb-2">Для кого</h3>
                        <p class="text-muted mb-3">Для начинающих специалистов, студентов и практиков, кто хочет системно прокачать кибербезопасность через реальные кейсы и проверяемые задания.</p>
                        <ul class="mb-0">
                            <li>Новичкам: понятный маршрут от базы к практике</li>
                            <li>Практикам: углубление в web/network/security процессы</li>
                        </ul>
                    </div>
                    <div class="col-lg-5">
                        <div class="service-card dark">
                            <div class="small text-white-50 mb-1">Воронка прогресса</div>
                            <div class="d-flex justify-content-between mb-1"><span>Отправлено</span><span>128</span></div>
                            <div class="d-flex justify-content-between mb-1"><span>Принято</span><span>94</span></div>
                            <div class="d-flex justify-content-between mb-3"><span>На проверке</span><span>11</span></div>
                            <a href="{{ route('register') }}" class="pill-btn accent w-100 text-center">Присоединиться</a>
                        </div>
                    </div>
                </div>
            </div>
        </section> -->

        <!-- <section class="section" id="why">
            <h3 class="mb-3 fw-bold" style="font-size:2rem;">Зачем</h3>
            <div class="table-box reveal p-4">
                <div class="row g-3">
                    <div class="col-md-4"><div class="service-card h-100"><h5 class="fw-bold">Прозрачный прогресс</h5><p class="mb-0 text-muted">Каждый ответ получает статус.</p></div></div>
                    <div class="col-md-4"><div class="service-card h-100"><h5 class="fw-bold">Еще зачем-то</h5><p class="mb-0 text-muted">тут описано зачем.</p></div></div>
                    <div class="col-md-4"><div class="service-card h-100"><h5 class="fw-bold">Карьерный рост</h5><p class="mb-0 text-muted">Портфолио выполненных задач и рейтинг топ‑специалистов.</p></div></div>
                </div>
            </div>
        </section> -->

        <section class="section reveal" id="experts">
            <h3 class="lime-section-title mb-3">Топовые специалисты платформы</h3>
            <div class="row g-3">
                @foreach(($experts ?? collect()) as $expert)
                <div class="col-md-4 reveal">
                    <div class="service-card dark expert-card {{ $loop->first ? 'active' : '' }}" data-expert-card>
                        <div class="d-flex justify-content-between align-items-center mb-2 expert-head">
                            <div class="d-flex align-items-center gap-2">
                                <img
                                    src="{{ $expert['avatar'] }}"
                                    alt="{{ $expert['name'] }}"
                                    referrerpolicy="no-referrer"
                                    crossorigin="anonymous"
                                    onerror="this.onerror=null;this.src='https://i.pravatar.cc/150?u={{ $expert['id'] }}';"
                                >
                                <div>
                                    <div class="fw-bold">{{ $expert['name'] }}</div>
                                    <div class="expert-meta">Принято: {{ $expert['accepted_count'] }} / Отправлено: {{ $expert['submitted_count'] }}</div>
                                </div>
                            </div>
                            <div class="fw-bold">{{ $expert['progress'] }}%</div>
                        </div>
                        <div class="meter"><span data-meter="{{ $expert['progress'] }}"></span></div>
                        <div class="expert-details">
                            <div class="small text-white-50 mb-1">Достижения:</div>
                            <ul class="expert-list text-white-50">
                                @forelse($expert['completed_tasks'] as $done)
                                    <li>{{ $done['task'] }} <span class="text-white-50">({{ $done['topic'] }})</span></li>
                                @empty
                                    <li>Пока нет принятых заданий</li>
                                @endforelse
                            </ul>
                            <div class="small text-white-50 mt-2 mb-1">Награды:</div>
                            <div>
                                @foreach($expert['awards'] as $award)
                                    <span class="expert-tag">{{ $award }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('register') }}" class="pill-btn accent">Хочу в рейтинг специалистов</a>
            </div>
        </section>
    </div>

    <div class="dino-runner" id="dinoRunner" aria-hidden="true">
        <img id="dinoFrame" src="{{ asset('img/dino-stand.png') }}" alt="">
    </div>
</div>

<script>
    (function () {
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var id = a.getAttribute('href');
                if (!id || id === '#') return;
                var el = document.querySelector(id);
                if (!el) return;
                e.preventDefault();
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        var reveal = document.querySelectorAll('.reveal');
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('in');
                io.unobserve(entry.target);
            });
        }, { threshold: 0.14 });
        reveal.forEach(function (el) { if (!el.classList.contains('in')) io.observe(el); });

        var meters = document.querySelectorAll('[data-meter]');
        var mio = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var v = parseInt(entry.target.getAttribute('data-meter') || '0', 10);
                v = Math.max(0, Math.min(100, v));
                entry.target.style.width = v + '%';
                mio.unobserve(entry.target);
            });
        }, { threshold: 0.3 });
        meters.forEach(function (el) { mio.observe(el); });

        document.querySelectorAll('[data-expert-card]').forEach(function (card) {
            card.addEventListener('click', function () {
                var isActive = card.classList.contains('active');
                document.querySelectorAll('[data-expert-card].active').forEach(function (item) {
                    item.classList.remove('active');
                });
                if (!isActive) card.classList.add('active');
            });
        });

        var dinoRunner = document.getElementById('dinoRunner');
        var dinoFrame = document.getElementById('dinoFrame');
        var dinoFrames = [
            "{{ asset('img/dino-stand.png') }}",
            "{{ asset('img/dino-left.png') }}",
            "{{ asset('img/dino-right.png') }}"
        ];
        var frameIndex = 0;

        setInterval(function () {
            if (!dinoFrame) return;
            frameIndex = (frameIndex + 1) % dinoFrames.length;
            dinoFrame.src = dinoFrames[frameIndex];
        }, 130);

        var updateRunnerPosition = function () {
            if (!dinoRunner) return;
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
            var doc = document.documentElement;
            var maxScroll = Math.max(1, doc.scrollHeight - window.innerHeight);
            var progress = Math.max(0, Math.min(1, scrollTop / maxScroll));
            var maxX = window.innerWidth - dinoRunner.offsetWidth - 8;
            var x = progress * Math.max(0, maxX);
            dinoRunner.style.transform = "translateX(" + x + "px)";
        };

        window.addEventListener('scroll', updateRunnerPosition, { passive: true });
        window.addEventListener('resize', updateRunnerPosition);
        updateRunnerPosition();
    })();
</script>
@endsection

