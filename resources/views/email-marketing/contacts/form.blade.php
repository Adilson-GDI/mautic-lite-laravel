@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>{{ $contact->exists ? 'Editar' : 'Novo' }} contato</h1>
        <p>Mantenha os dados do contato e suas listas de relacionamento.</p>
    </div>
    <div class="actions">
        <a class="btn secondary" href="{{ route('email-marketing.contacts.index') }}">Voltar</a>
    </div>
</div>

<form class="form-shell" method="post" action="{{ $contact->exists ? route('email-marketing.contacts.update',$contact) : route('email-marketing.contacts.store') }}">
    @csrf
    @if($contact->exists) @method('put') @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Dados do contato</h2>
            <p>Informacoes principais usadas nas campanhas.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" value="{{ old('name',$contact->name) }}" autofocus>
                </div>
                <div class="form-field">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email',$contact->email) }}" required>
                </div>
                <div class="form-field third">
                    <label for="phone">Telefone</label>
                    <input id="phone" name="phone" value="{{ old('phone',$contact->phone) }}">
                </div>
                <div class="form-field third">
                    <label for="document">Documento</label>
                    <input id="document" name="document" value="{{ old('document',$contact->document) }}">
                </div>
                <div class="form-field third">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        @foreach(['active','unsubscribed','bounced','invalid'] as $s)
                            <option @selected(old('status',$contact->status ?: 'active')===$s)>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field full">
                    <label for="source">Origem</label>
                    <input id="source" name="source" value="{{ old('source',$contact->source) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Listas</h2>
            <p>Associe o contato aos grupos desejados.</p>
        </div>
        <div class="form-card-body">
            <div class="form-field full">
                <label for="lists">Listas</label>
                <select id="lists" name="lists[]" multiple size="8">
                    @foreach($lists as $list)
                        <option value="{{ $list->id }}" @selected(in_array($list->id, old('lists', $contact->lists->pluck('id')->all())))>{{ $list->name }}</option>
                    @endforeach
                </select>
                <span class="form-help">Segure Ctrl para selecionar varias listas.</span>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn">Salvar</button>
        <a class="btn secondary" href="{{ route('email-marketing.contacts.index') }}">Cancelar</a>
    </div>
</form>
@endsection
