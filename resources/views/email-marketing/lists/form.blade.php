@extends('layouts.admin')
@section('content')
<h1>{{ $list->exists ? 'Editar' : 'Nova' }} lista</h1>
<form method="post" action="{{ $list->exists ? route('email-marketing.lists.update',$list) : route('email-marketing.lists.store') }}">@csrf @if($list->exists) @method('put') @endif
<label>Nome<input name="name" value="{{ old('name',$list->name) }}" required></label>
<label>Descricao<textarea name="description">{{ old('description',$list->description) }}</textarea></label>
<label><input style="width:auto" type="checkbox" name="active" value="1" @checked(old('active',$list->active ?? true))> Ativa</label>
<div class="field"><label>Contatos<select name="contacts[]" multiple size="10">@foreach($contacts as $contact)<option value="{{ $contact->id }}" @selected(in_array($contact->id, old('contacts', $list->contacts->pluck('id')->all())))>{{ $contact->email }}</option>@endforeach</select></label></div>
<button class="btn">Salvar</button>
</form>
@endsection
