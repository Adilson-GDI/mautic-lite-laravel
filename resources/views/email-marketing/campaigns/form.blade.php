@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>{{ $campaign->exists ? 'Editar' : 'Nova' }} campanha</h1>
        <p>Configure o envio, conteudo e listas da campanha.</p>
    </div>
    <div class="actions">
        <a class="btn secondary" href="{{ route('email-marketing.campaigns.index') }}">Voltar</a>
    </div>
</div>

<form class="form-shell" method="post" action="{{ $campaign->exists ? route('email-marketing.campaigns.update',$campaign) : route('email-marketing.campaigns.store') }}">
    @csrf
    @if($campaign->exists) @method('put') @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Configuracao</h2>
            <p>Defina nome, provedor e status inicial.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" value="{{ old('name',$campaign->name) }}" required autofocus>
                </div>
                <div class="form-field">
                    <label for="provider_id">Provedor</label>
                    <select id="provider_id" name="provider_id" required>
                        @foreach($providers as $provider)
                            <option value="{{ $provider->id }}" @selected(old('provider_id',$campaign->provider_id)==$provider->id)>{{ $provider->name }} ({{ $provider->type }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        @foreach(['draft','scheduled','paused','canceled'] as $s)
                            <option @selected(old('status',$campaign->status ?: 'draft')===$s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="scheduled_at">Agendada para</label>
                    <input id="scheduled_at" type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\TH:i')) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Conteudo</h2>
            <p>Monte o assunto, preheader e corpo da mensagem.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="subject">Assunto</label>
                    <input id="subject" name="subject" value="{{ old('subject',$campaign->subject) }}" required>
                </div>
                <div class="form-field">
                    <label for="preheader">Preheader</label>
                    <input id="preheader" name="preheader" value="{{ old('preheader',$campaign->preheader) }}">
                </div>
                <div class="form-field full">
                    <label for="html_body">HTML</label>
                    <textarea id="html_body" name="html_body" required style="min-height:260px">{{ old('html_body',$campaign->html_body) }}</textarea>
                </div>
                <div class="form-field full">
                    <label for="text_body">Texto puro</label>
                    <textarea id="text_body" name="text_body">{{ old('text_body',$campaign->text_body) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Listas</h2>
            <p>Escolha os grupos que receberao esta campanha.</p>
        </div>
        <div class="form-card-body">
            <div class="form-field full">
                <label for="lists">Listas</label>
                <select id="lists" name="lists[]" multiple size="8" required>
                    @foreach($lists as $list)
                        <option value="{{ $list->id }}" @selected(in_array($list->id, old('lists', $campaign->lists->pluck('id')->all())))>{{ $list->name }}</option>
                    @endforeach
                </select>
                <span class="form-help">Segure Ctrl para selecionar varias listas.</span>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn">Salvar</button>
        <a class="btn secondary" href="{{ route('email-marketing.campaigns.index') }}">Cancelar</a>
    </div>
</form>
@endsection
