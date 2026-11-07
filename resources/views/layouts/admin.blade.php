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
        body { background-color: #f0f2f5; }
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
        .sidebar .nav-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        .sidebar .nav-link.active { background: #1a3a6e; color: #fff; }
        .sidebar .nav-section {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #6c757d;
            padding: .5rem .75rem .25rem;
            margin-top: .5rem;
        }
        .main-content { flex: 1; min-width: 0; }
        .topbar { background: #fff; border-bottom: 1px solid #dee2e6; }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">

    {{-- Sidebar --}}
    <div class="sidebar d-flex flex-column p-3">
        <a href="{{ route('home') }}" class="text-white text-decoration-none mb-3 d-block">
            <i class="bi bi-journal-bookmark-fill me-1"></i>
            <strong>SIS/PSC</strong>
            <small class="text-muted d-block" style="font-size:.7rem">Painel Administrativo</small>
        </a>
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

            <span class="nav-section">Comunidade</span>
            <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}"
               href="{{ route('admin.usuarios.index') }}">
                <i class="bi bi-people me-2"></i>Usuários
            </a>
            <a class="nav-link {{ request()->routeIs('admin.moderacao.*') ? 'active' : '' }}"
               href="{{ route('admin.moderacao.index') }}">
                <i class="bi bi-shield-check me-2"></i>Moderação
                @php $pendentes = \App\Models\Denuncia::where('status','pendente')->count() @endphp
                @if($pendentes > 0)
                    <span class="badge bg-danger ms-1">{{ $pendentes }}</span>
                @endif
            </a>

            <hr class="border-secondary mt-auto">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="bi bi-arrow-left me-2"></i>Ver site
            </a>
            <a class="nav-link text-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('admin-logout').submit();">
                <i class="bi bi-box-arrow-right me-2"></i>Sair
            </a>
            <form id="admin-logout" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </nav>
    </div>

    {{-- Conteúdo principal --}}
    <div class="main-content">
        <div class="topbar px-4 py-2 d-flex align-items-center gap-2">
            <nav aria-label="breadcrumb" class="flex-grow-1">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
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
    </div>
</div>

@stack('scripts')
</body>
</html>
