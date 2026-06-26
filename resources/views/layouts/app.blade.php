<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кибер-Лаб - @yield('title')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
    <link rel="icon" href="{{ asset('favicon-96x96.png') }}" type="image/png" sizes="96x96">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @php($layoutCssPath = resource_path('css/views/layouts-app.css'))
    @if (file_exists($layoutCssPath))
        <style>{!! file_get_contents($layoutCssPath) !!}</style>
    @endif
    @stack('page_styles')
</head>
<body>

    @if (Auth::check() && !Request::is('level/*'))
    <nav class="navbar navbar-expand-lg mb-4 shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
                <span class="accent-color">Кибер</span>-Лаб
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="d-flex align-items-center ms-auto">
                    <a href="{{ route('home') }}" class="nav-link me-4 fw-medium text-dark">Домой</a>
                    @if(Auth::user()?->canAccessAdminPanel())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link me-4 fw-medium text-dark">
                            {{ Auth::user()->isAdmin() ? 'Админка' : 'Панель эксперта' }}
                        </a>
                    @endif
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none text-dark dropdown-toggle" data-bs-toggle="dropdown">
                            <span class="me-2 fw-medium">{{ Auth::user()->name }}</span>
                            <img src="{{ \App\Support\PublicImage::avatar(Auth::user()) }}" alt="avatar" class="rounded-circle border" style="width: 40px; height: 40px;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 p-2">
                            <li><a class="dropdown-item rounded py-2" href="{{ route('profile') }}">Профиль</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout', [], false) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded py-2 text-danger">Выйти</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    @endif

    @if (Auth::check())
        @if (Request::is('level/*'))
            @yield('content')
        @else
            <div class="container py-4">
                @yield('content')
            </div>
        @endif
    @else
        @yield('content')
    @endif

    @if (Auth::check() && !Request::is('level/*'))
    <div class="container">
        <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
            <div class="col-md-4 d-flex align-items-center">
                <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
                    <span class="accent-color">Cyber-Lab</span>
                </a>
                <span class="text-muted">© 2026 Кустова Екатерина Владимировна</span>
            </div>

            <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#twitter"></use></svg></a></li>
                <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"></use></svg></a></li>
                <li class="ms-3"><a class="text-muted" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"></use></svg></a></li>
            </ul>
        </footer>
    </div>
    @endif

    @if (session('success') || session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1055;">
        @if (session('success'))
        <div class="toast align-items-center text-bg-success border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" id="successToast">
            <div class="d-flex">
                <div class="toast-body fw-medium">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
            </div>
        </div>
        @endif
        @if (session('error'))
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="errorToast">
            <div class="d-flex">
                <div class="toast-body fw-medium">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Закрыть"></button>
            </div>
        </div>
        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ['successToast', 'errorToast'].forEach(function(id) {
                var toastEl = document.getElementById(id);
                if (toastEl) {
                    new bootstrap.Toast(toastEl, { delay: 4000 }).show();
                }
            });
        });
    </script>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('page_scripts')
</body>
</html>
