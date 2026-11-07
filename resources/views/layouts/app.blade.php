<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Banco de Provas') — SIS/PSC</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --bs-primary: #1a3a6e;
            --color-sis: #003087;
            --color-psc: #006400;
        }
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; letter-spacing: -0.5px; }
        .navbar-brand span.sis { color: #4a90d9; }
        .navbar-brand span.psc { color: #5cb85c; }
        .badge-oficial {
            background: linear-gradient(135deg, #1a3a6e 0%, #2d5f9e 100%);
            color: #fff;
            font-size: .7rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: .3px;
        }
        .badge-comunidade {
            background: #e8f4fd;
            color: #1a6fa8;
            font-size: .7rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
        }
        .card-questao { transition: box-shadow .2s; }
        .card-questao:hover { box-shadow: 0 4px 15px rgba(0,0,0,.1); }
        .footer a:hover { color: #fff !important; }
        .resposta-card { border-left: 4px solid #dee2e6; }
        .resposta-card.is-oficial { border-left-color: #1a3a6e; }
        .resposta-card.is-comunidade { border-left-color: #5bc0de; }
    </style>
    @stack('styles')
</head>
<body>

{{-- Navbar principal --}}
<nav class="navbar navbar-expand-md navbar-dark shadow-sm" style="background-color:#1a3a6e">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-journal-bookmark-fill me-1"></i>
            <span class="sis">SIS</span>/<span class="psc">PSC</span>
            <small class="fw-normal opacity-75 d-none d-sm-inline" style="font-size:.75rem"> Banco de Provas</small>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('provas.*') ? 'active' : '' }}"
                       href="{{ route('provas.index') }}">
                        <i class="bi bi-file-earmark-text me-1"></i>Provas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('questoes.*') ? 'active' : '' }}"
                       href="{{ route('questoes.index') }}">
                        <i class="bi bi-question-circle me-1"></i>Questões
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link text-warning" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Admin
                            </a>
                        </li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('perfil.show') }}">
                                <i class="bi bi-person me-2"></i>Meu Perfil
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sair
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Entrar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light btn-sm ms-2 px-3"
                           href="{{ route('register') }}">
                            Cadastrar-se
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- Alertas globais --}}
<div class="container mt-3">
    @if(session('sucesso'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('sucesso') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('erro'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('erro') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('aviso'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('aviso') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

<main class="py-4">
    @yield('content')
</main>

<footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
        <div class="row g-3">
            <div class="col-md-4">
                <h6 class="fw-bold"><i class="bi bi-journal-bookmark-fill me-1"></i> SIS/PSC — Banco de Provas</h6>
                <p class="text-white-50 small mb-0">
                    Plataforma educacional para consulta de provas e questões do SIS/UEA e PSC/UFAM.
                </p>
            </div>
            <div class="col-md-2">
                <h6 class="fw-bold text-white-50 small text-uppercase">Conteúdo</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('provas.index') }}" class="text-white-50 text-decoration-none">Provas</a></li>
                    <li><a href="{{ route('questoes.index') }}" class="text-white-50 text-decoration-none">Questões</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="fw-bold text-white-50 small text-uppercase">Instituições</h6>
                <ul class="list-unstyled small">
                    <li><a href="https://www.uea.edu.br" target="_blank" class="text-white-50 text-decoration-none">
                        UEA — Universidade do Estado do Amazonas
                    </a></li>
                    <li><a href="https://www.ufam.edu.br" target="_blank" class="text-white-50 text-decoration-none">
                        UFAM — Universidade Federal do Amazonas
                    </a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h6 class="fw-bold text-white-50 small text-uppercase">Conta</h6>
                <ul class="list-unstyled small">
                    @guest
                        <li><a href="{{ route('login') }}" class="text-white-50 text-decoration-none">Entrar</a></li>
                        <li><a href="{{ route('register') }}" class="text-white-50 text-decoration-none">Cadastrar-se</a></li>
                    @else
                        <li><a href="{{ route('perfil.show') }}" class="text-white-50 text-decoration-none">Meu Perfil</a></li>
                    @endguest
                </ul>
            </div>
        </div>
        <hr class="border-secondary mt-3">
        <p class="text-white-50 text-center small mb-0">
            Banco de Provas SIS/PSC — Plataforma educacional sem fins lucrativos.
            Os documentos são de propriedade das respectivas instituições.
        </p>
    </div>
</footer>

@stack('scripts')
</body>
</html>
