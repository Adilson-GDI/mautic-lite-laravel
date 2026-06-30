@extends('layouts.admin')
@section('content')
<h1>{{ $contact->exists ? 'Editar' : 'Novo' }} contato</h1>
<form method="post" action="{{ $contact->exists ? route('email-marketing.contacts.update',$contact) : route('email-marketing.contacts.store') }}">@csrf @if($contact->exists) @method('put') @endif
<div class="row"><label>Nome<input name="name" value="{{ old('name',$contact->name) }}"></label><label>E-mail<input type="email" name="email" value="{{ old('email',$contact->email) }}" required></label><label>Telefone<input name="phone" value="{{ old('phone',$contact->phone) }}"></label><label>Documento<input name="document" value="{{ old('document',$contact->document) }}"></label><label>Status<select name="status">@foreach(['active','unsubscribed','bounced','invalid'] as $s)<option @selected(old('status',$contact->status ?: 'active')===$s)>{{ $s }}</option>@endforeach</select></label><label>Origem<input name="source" value="{{ old('source',$contact->source) }}"></label></div>
<div class="field"><label>Listas<select name="lists[]" multiple size="6">@foreach($lists as $list)<option value="{{ $list->id }}" @selected(in_array($list->id, old('lists', $contact->lists->pluck('id')->all())))>{{ $list->name }}</option>@endforeach</select></label></div>
<button class="btn">Salvar</button>
</form>
@endsection
