@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>{{ $user->exists ? 'Editar' : 'Novo' }} usuario</h1>
        <p>Defina os dados de acesso ao painel.</p>
    </div>
    <div class="actions"><a class="btn secondary" href="{{ route('users.index') }}">Voltar</a></div>
</div>

<form class="form-shell" method="post" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">
    @csrf
    @if($user->exists) @method('put') @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Acesso</h2>
            <p>{{ $user->exists ? 'Deixe a senha em branco para manter a atual.' : 'Crie uma senha com pelo menos 8 caracteres.' }}</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field"><label for="name">Nome</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus></div>
                <div class="form-field"><label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
                <div class="form-field"><label for="password">Senha</label><input id="password" type="password" name="password" @required(! $user->exists)></div>
                <div class="form-field"><label for="password_confirmation">Confirmar senha</label><input id="password_confirmation" type="password" name="password_confirmation" @required(! $user->exists)></div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn">Salvar</button>
        <a class="btn secondary" href="{{ route('users.index') }}">Cancelar</a>
    </div>
</form>
@endsection
