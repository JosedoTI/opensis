@extends('layouts.app')

@section('title', 'Início')

@section('content')
<div class="container">
    <div class="alert alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        Bem-vindo(a), <strong>{{ Auth::user()->name }}</strong>!
    </div>
    @php $redirect = Auth::user()->isAdmin() ? route('admin.dashboard') : route('questoes.index') @endphp
    <script>window.location = '{{ $redirect }}';</script>
    <p class="text-muted">Redirecionando... <a href="{{ $redirect }}">Clique aqui</a> se não for redirecionado.</p>
</div>
@endsection
