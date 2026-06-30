@extends('layouts.admin')
@section('content')
<h1>{{ $provider->exists ? 'Editar' : 'Novo' }} provedor</h1>
<form method="post" action="{{ $provider->exists ? route('email-marketing.providers.update', $provider) : route('email-marketing.providers.store') }}">
@csrf @if($provider->exists) @method('put') @endif
<div class="row">
    <div class="field"><label>Nome<input name="name" value="{{ old('name', $provider->name) }}" required></label></div>
    <div class="field"><label>Tipo<select name="type"><option value="google_workspace" @selected(old('type',$provider->type)==='google_workspace')>Google Workspace / SMTP</option><option value="aws_ses" @selected(old('type',$provider->type)==='aws_ses')>AWS SES</option></select></label></div>
    <div class="field"><label>From name<input name="from_name" value="{{ old('from_name', $provider->from_name) }}" required></label></div>
    <div class="field"><label>From e-mail<input type="email" name="from_email" value="{{ old('from_email', $provider->from_email) }}" required></label></div>
    <div class="field"><label>Reply-to<input type="email" name="reply_to" value="{{ old('reply_to', $provider->reply_to) }}"></label></div>
    <div class="field"><label>SMTP host<input name="smtp_host" value="{{ old('smtp_host', $provider->smtp_host) }}"></label></div>
    <div class="field"><label>SMTP port<input type="number" name="smtp_port" value="{{ old('smtp_port', $provider->smtp_port) }}"></label></div>
    <div class="field"><label>SMTP usuario<input name="smtp_username" value="{{ old('smtp_username', $provider->smtp_username) }}"></label></div>
    <div class="field"><label>SMTP senha<input type="password" name="smtp_password" placeholder="{{ $provider->exists ? 'manter atual' : '' }}"></label></div>
    <div class="field"><label>Criptografia<select name="smtp_encryption"><option value="">Nenhuma</option><option value="tls" @selected(old('smtp_encryption',$provider->smtp_encryption)==='tls')>TLS</option><option value="ssl" @selected(old('smtp_encryption',$provider->smtp_encryption)==='ssl')>SSL</option></select></label></div>
    <div class="field"><label>AWS key<input type="password" name="aws_key" placeholder="{{ $provider->exists ? 'manter atual' : '' }}"></label></div>
    <div class="field"><label>AWS secret<input type="password" name="aws_secret" placeholder="{{ $provider->exists ? 'manter atual' : '' }}"></label></div>
    <div class="field"><label>AWS region<input name="aws_region" value="{{ old('aws_region', $provider->aws_region) }}"></label></div>
    <div class="field"><label>Limite diario<input type="number" name="daily_limit" value="{{ old('daily_limit', $provider->daily_limit ?? 0) }}"></label></div>
    <div class="field"><label>Limite horario<input type="number" name="hourly_limit" value="{{ old('hourly_limit', $provider->hourly_limit ?? 0) }}"></label></div>
</div>
<label><input style="width:auto" type="checkbox" name="active" value="1" @checked(old('active', $provider->active ?? true))> Ativo</label>
<p><button class="btn">Salvar</button></p>
</form>
@if($provider->exists)
<div class="card"><form method="post" action="{{ route('email-marketing.providers.test', $provider) }}">@csrf
    <label>Enviar teste para<input type="email" name="email" required></label><p><button class="btn secondary">Testar envio</button></p>
</form></div>
@endif
@endsection
