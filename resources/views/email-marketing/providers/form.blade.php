@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>{{ $provider->exists ? 'Editar' : 'Novo' }} provedor</h1>
        <p>Configure a conta usada para enviar campanhas.</p>
    </div>
    <div class="actions">
        <a class="btn secondary" href="{{ route('email-marketing.providers.index') }}">Voltar</a>
    </div>
</div>

<form class="form-shell" method="post" action="{{ $provider->exists ? route('email-marketing.providers.update', $provider) : route('email-marketing.providers.store') }}">
    @csrf
    @if($provider->exists) @method('put') @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Identidade</h2>
            <p>Dados exibidos como remetente das mensagens.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" value="{{ old('name', $provider->name) }}" required autofocus>
                </div>
                <div class="form-field">
                    <label for="type">Tipo</label>
                    <select id="type" name="type">
                        <option value="google_workspace" @selected(old('type',$provider->type)==='google_workspace')>Google Workspace / SMTP</option>
                        <option value="aws_ses" @selected(old('type',$provider->type)==='aws_ses')>AWS SES</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="from_name">From name</label>
                    <input id="from_name" name="from_name" value="{{ old('from_name', $provider->from_name) }}" required>
                </div>
                <div class="form-field">
                    <label for="from_email">From e-mail</label>
                    <input id="from_email" type="email" name="from_email" value="{{ old('from_email', $provider->from_email) }}" required>
                </div>
                <div class="form-field">
                    <label for="reply_to">Reply-to</label>
                    <input id="reply_to" type="email" name="reply_to" value="{{ old('reply_to', $provider->reply_to) }}">
                </div>
                <div class="form-field">
                    <label class="form-label">Status</label>
                    <label class="switch-line">
                        <input type="checkbox" name="active" value="1" @checked(old('active', $provider->active ?? true))>
                        Ativo
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>SMTP</h2>
            <p>Use estes campos para Google Workspace ou outro servidor SMTP.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="smtp_host">SMTP host</label>
                    <input id="smtp_host" name="smtp_host" value="{{ old('smtp_host', $provider->smtp_host) }}" placeholder="smtp.gmail.com">
                </div>
                <div class="form-field quarter">
                    <label for="smtp_port">Porta</label>
                    <input id="smtp_port" type="number" name="smtp_port" value="{{ old('smtp_port', $provider->smtp_port) }}" placeholder="587">
                </div>
                <div class="form-field quarter">
                    <label for="smtp_encryption">Criptografia</label>
                    <select id="smtp_encryption" name="smtp_encryption">
                        <option value="">Nenhuma</option>
                        <option value="tls" @selected(old('smtp_encryption',$provider->smtp_encryption)==='tls')>TLS</option>
                        <option value="ssl" @selected(old('smtp_encryption',$provider->smtp_encryption)==='ssl')>SSL</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="smtp_username">SMTP usuario</label>
                    <input id="smtp_username" name="smtp_username" value="{{ old('smtp_username', $provider->smtp_username) }}">
                </div>
                <div class="form-field">
                    <label for="smtp_password">SMTP senha</label>
                    <input id="smtp_password" type="password" name="smtp_password" placeholder="{{ $provider->exists ? 'manter atual' : 'senha de app ou senha SMTP' }}">
                    <span class="form-help">Ao editar, deixe em branco para manter a senha atual.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>AWS SES</h2>
            <p>Preencha apenas quando o tipo selecionado for AWS SES.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="aws_key">AWS key</label>
                    <input id="aws_key" type="password" name="aws_key" placeholder="{{ $provider->exists ? 'manter atual' : '' }}">
                </div>
                <div class="form-field">
                    <label for="aws_secret">AWS secret</label>
                    <input id="aws_secret" type="password" name="aws_secret" placeholder="{{ $provider->exists ? 'manter atual' : '' }}">
                </div>
                <div class="form-field">
                    <label for="aws_region">AWS region</label>
                    <input id="aws_region" name="aws_region" value="{{ old('aws_region', $provider->aws_region) }}" placeholder="us-east-1">
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Limites</h2>
            <p>Controle o volume de envio permitido para este provedor.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="daily_limit">Limite diario</label>
                    <input id="daily_limit" type="number" name="daily_limit" value="{{ old('daily_limit', $provider->daily_limit ?? 0) }}">
                </div>
                <div class="form-field">
                    <label for="hourly_limit">Limite horario</label>
                    <input id="hourly_limit" type="number" name="hourly_limit" value="{{ old('hourly_limit', $provider->hourly_limit ?? 0) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn">Salvar</button>
        <a class="btn secondary" href="{{ route('email-marketing.providers.index') }}">Cancelar</a>
    </div>
</form>

@if($provider->exists)
<div class="form-shell">
    <div class="form-card">
        <div class="form-card-header">
            <h2>Teste de envio</h2>
            <p>Envie uma mensagem simples para validar a configuracao.</p>
        </div>
        <div class="form-card-body">
            <form method="post" action="{{ route('email-marketing.providers.test', $provider) }}">
                @csrf
                <div class="form-grid">
                    <div class="form-field">
                        <label for="test_email">Enviar teste para</label>
                        <input id="test_email" type="email" name="email" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn secondary">Testar envio</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
