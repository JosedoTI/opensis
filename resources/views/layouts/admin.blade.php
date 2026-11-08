
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Painel Admin') — SIS/PSC</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f0f2f5;
        }

        /* Menu lateral */
        .sidebar {
            min-height: 100vh;
            background: #1a1d23;
            width: 250px;
            flex-shrink: 0;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            border-radius: 6px;
            padding: .5rem .75rem;
            font-size: .9rem;
        }

        .sidebar .nav-link:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }

        .sidebar .nav-link.active {
            background: #1a3a6e;
            color: #fff;
        }

        .sidebar .nav-section {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6c757d;
            padding: .5rem .75rem .25rem;
            margin-top: .5rem;
        }

        .main-content {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        /* Fundo escuro atrás do menu no celular */
        .sidebar-backdrop {
            display: none;
        }

        /* Ajustes para celular e tablets */
        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1050;

                width: min(280px, 85vw);
                height: 100vh;
                min-height: 100vh;

                overflow-y: auto;
                transform: translateX(-100%);
                transition: transform .25s ease-in-out;
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                z-index: 1040;
                background: rgba(0, 0, 0, .5);
                border: 0;
                padding: 0;
            }

            .sidebar-backdrop.is-visible {
                display: block;
            }

            .main-content {
                width: 100%;
                max-width: 100%;
            }

            .topbar {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .main-content > .p-4 {
                padding: 1rem !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .sidebar {
                transition: none;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="d-flex">

    {{-- Menu lateral --}}
    <aside class="sidebar d-flex flex-column p-3" id="adminSidebar">

        <div class="d-flex align-items-start justify-content-between mb-3">

            <a href="{{ route('home') }}" class="text-white text-decoration-none d-block">
                <i class="bi bi-journal-bookmark-fill me-1"></i>
                <strong>SIS/PSC</strong>
                <small class="text-muted d-block" style="font-size:.7rem">
                    Painel Administrativo
                </small>
            </a>

            {{-- Botão fechar: somente celular --}}
            <button
                type="button"
                class="btn btn-outline-light btn-sm d-lg-none"
                id="closeSidebar"
                aria-label="Fechar menu"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

        <hr class="border-secondary">

        <nav class="nav flex-column gap-1">

            <span class="nav-section">Principal</span>

            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i>Dashboard
            </a>

            <span class="nav-section">Conteúdo</span>

            <a class="nav-link {{ request()->routeIs('admin.instituicoes.*') ? 'active' : '' }}"
               href="{{ route('admin.instituicoes.index') }}">
                <i class="bi bi-building me-2"></i>Instituições
            </a>

            <a class="nav-link {{ request()->routeIs('admin.provas.*') ? 'active' : '' }}"
               href="{{ route('admin.provas.index') }}">
                <i class="bi bi-file-earmark-text me-2"></i>Provas
            </a>

            <a class="nav-link {{ request()->routeIs('admin.questoes.*') ? 'active' : '' }}"
               href="{{ route('admin.questoes.index') }}">
                <i class="bi bi-question-circle me-2"></i>Questões
            </a>

            <a class="nav-link {{ request()->routeIs('admin.disciplinas.*') ? 'active' : '' }}"
               href="{{ route('admin.disciplinas.index') }}">
                <i class="bi bi-tags me-2"></i>Disciplinas
            </a>

            <a class="nav-link {{ request()->routeIs('admin.documentos.*') ? 'active' : '' }}"
               href="{{ route('admin.documentos.index') }}">
                <i class="bi bi-folder2-open me-2"></i>Documentos
                @php $semDoc = \App\Models\Prova::where('ativa', true)->whereDoesntHave('documentos', fn($q) => $q->where('ativo', true))->count() @endphp
                @if($semDoc > 0)
                    <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">{{ $semDoc }}</span>
                @endif
            </a>

            <span class="nav-section">Comunidade</span>

            <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}"
               href="{{ route('admin.usuarios.index') }}">
                <i class="bi bi-people me-2"></i>Usuários
            </a>

            <a class="nav-link {{ request()->routeIs('admin.moderacao.*') ? 'active' : '' }}"
               href="{{ route('admin.moderacao.index') }}">
                <i class="bi bi-shield-check me-2"></i>Moderação

                @php
                    $pendentes = \App\Models\Denuncia::where('status', 'pendente')->count()
                @endphp

                @if($pendentes > 0)
                    <span class="badge bg-danger ms-1">{{ $pendentes }}</span>
                @endif
            </a>

            <hr class="border-secondary mt-auto">

            <a class="nav-link" href="{{ route('home') }}">
                <i class="bi bi-arrow-left me-2"></i>Ver site
            </a>

            <a class="nav-link text-danger"
               href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('admin-logout').submit();">
                <i class="bi bi-box-arrow-right me-2"></i>Sair
            </a>

            <form id="admin-logout" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </nav>
    </aside>

    {{-- Fundo escuro ao abrir o menu --}}
    <button
        type="button"
        class="sidebar-backdrop"
        id="sidebarBackdrop"
        aria-label="Fechar menu"
        tabindex="-1"
    ></button>

    {{-- Conteúdo principal --}}
    <main class="main-content">

        <div class="topbar px-4 py-2 d-flex align-items-center gap-2">

            {{-- Botão abrir menu: somente celular --}}
            <button
                type="button"
                class="btn btn-outline-secondary btn-sm d-lg-none"
                id="openSidebar"
                aria-label="Abrir menu"
                aria-controls="adminSidebar"
                aria-expanded="false"
            >
                <i class="bi bi-list fs-5"></i>
            </button>

            <nav aria-label="breadcrumb" class="flex-grow-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Admin</a>
                    </li>
                    @yield('breadcrumb')
                </ol>
            </nav>

            <span class="text-muted small">
                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
            </span>

        </div>

        <div class="p-4">

            @if(session('sucesso'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('sucesso') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('erro'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('erro') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-x-circle me-2"></i>

                    <ul class="mb-0">
                        @foreach($errors->all() as $erro)
                            <li>{{ $erro }}</li>
                        @endforeach
                    </ul>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')

        </div>
    </main>

</div>

@stack('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('adminSidebar');
        const openButton = document.getElementById('openSidebar');
        const closeButton = document.getElementById('closeSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.add('is-open');
            backdrop.classList.add('is-visible');
            openButton.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('is-open');
            backdrop.classList.remove('is-visible');
            openButton.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
        }

        openButton.addEventListener('click', openSidebar);
        closeButton.addEventListener('click', closeSidebar);
        backdrop.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });

        sidebar.querySelectorAll('a.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth < 992) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    });
</script>

</body>
</html>
