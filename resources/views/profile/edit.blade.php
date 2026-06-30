@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Perfil</h1>
        <p>Atualize seus dados e senha de acesso.</p>
    </div>
</div>

<div class="form-shell">
    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('put')
        <div class="form-card">
            <div class="form-card-header">
                <h2>Dados pessoais</h2>
                <p>Essas informacoes identificam seu usuario no painel.</p>
            </div>
            <div class="form-card-body">
                <div class="form-grid">
                    <div class="form-field"><label for="name">Nome</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required></div>
                    <div class="form-field"><label for="email">E-mail</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required></div>
                </div>
                <div class="form-actions"><button class="btn">Salvar perfil</button></div>
            </div>
        </div>
    </form>

    <form method="post" action="{{ route('profile.password') }}">
        @csrf
        @method('put')
        <div class="form-card">
            <div class="form-card-header">
                <h2>Alterar senha</h2>
                <p>Informe sua senha atual para cadastrar uma nova.</p>
            </div>
            <div class="form-card-body">
                <div class="form-grid">
                    <div class="form-field full"><label for="current_password">Senha atual</label><input id="current_password" type="password" name="current_password" required></div>
                    <div class="form-field"><label for="password">Nova senha</label><input id="password" type="password" name="password" required></div>
                    <div class="form-field"><label for="password_confirmation">Confirmar nova senha</label><input id="password_confirmation" type="password" name="password_confirmation" required></div>
                </div>
                <div class="form-actions"><button class="btn secondary">Alterar senha</button></div>
            </div>
        </div>
    </form>
</div>
@endsection
