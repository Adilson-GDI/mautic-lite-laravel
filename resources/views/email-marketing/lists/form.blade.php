@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>{{ $list->exists ? 'Editar' : 'Nova' }} lista</h1>
        <p>Organize contatos em grupos para segmentar campanhas.</p>
    </div>
    <div class="actions">
        <a class="btn secondary" href="{{ route('email-marketing.lists.index') }}">Voltar</a>
    </div>
</div>

<form class="form-shell" method="post" action="{{ $list->exists ? route('email-marketing.lists.update',$list) : route('email-marketing.lists.store') }}">
    @csrf
    @if($list->exists) @method('put') @endif

    <div class="form-card">
        <div class="form-card-header">
            <h2>Dados da lista</h2>
            <p>Defina o nome, descricao e status de uso.</p>
        </div>
        <div class="form-card-body">
            <div class="form-grid">
                <div class="form-field">
                    <label for="name">Nome</label>
                    <input id="name" name="name" value="{{ old('name',$list->name) }}" required autofocus>
                </div>

                <div class="form-field">
                    <label class="form-label">Status</label>
                    <label class="switch-line">
                        <input type="checkbox" name="active" value="1" @checked(old('active',$list->active ?? true))>
                        Ativa
                    </label>
                </div>

                <div class="form-field full">
                    <label for="description">Descricao</label>
                    <textarea id="description" name="description">{{ old('description',$list->description) }}</textarea>
                    <span class="form-help">Use uma descricao curta para identificar o objetivo desta lista.</span>
                </div>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <h2>Contatos</h2>
            <p>Selecione um ou mais contatos para associar a esta lista.</p>
        </div>
        <div class="form-card-body">
            <div class="form-field full">
                <label for="contacts">Contatos</label>
                <select id="contacts" name="contacts[]" multiple size="10">
                    @foreach($contacts as $contact)
                        <option value="{{ $contact->id }}" @selected(in_array($contact->id, old('contacts', $list->contacts->pluck('id')->all())))>{{ $contact->name ? $contact->name.' - ' : '' }}{{ $contact->email }}</option>
                    @endforeach
                </select>
                <span class="form-help">Segure Ctrl para selecionar varios contatos.</span>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn">Salvar</button>
        <a class="btn secondary" href="{{ route('email-marketing.lists.index') }}">Cancelar</a>
    </div>
</form>
@endsection
