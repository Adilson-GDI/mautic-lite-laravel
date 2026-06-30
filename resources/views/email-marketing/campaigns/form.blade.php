@extends('layouts.admin')
@section('content')
<h1>{{ $campaign->exists ? 'Editar' : 'Nova' }} campanha</h1>
<form method="post" action="{{ $campaign->exists ? route('email-marketing.campaigns.update',$campaign) : route('email-marketing.campaigns.store') }}">@csrf @if($campaign->exists) @method('put') @endif
<div class="row">
    <label>Nome<input name="name" value="{{ old('name',$campaign->name) }}" required></label>
    <label>Provedor<select name="provider_id" required>@foreach($providers as $provider)<option value="{{ $provider->id }}" @selected(old('provider_id',$campaign->provider_id)==$provider->id)>{{ $provider->name }} ({{ $provider->type }})</option>@endforeach</select></label>
    <label>Status<select name="status">@foreach(['draft','scheduled','paused','canceled'] as $s)<option @selected(old('status',$campaign->status ?: 'draft')===$s)>{{ $s }}</option>@endforeach</select></label>
    <label>Agendada para<input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\TH:i')) }}"></label>
</div>
<label>Assunto<input name="subject" value="{{ old('subject',$campaign->subject) }}" required></label>
<label>Preheader<input name="preheader" value="{{ old('preheader',$campaign->preheader) }}"></label>
<label>HTML<textarea name="html_body" required>{{ old('html_body',$campaign->html_body) }}</textarea></label>
<label>Texto puro<textarea name="text_body">{{ old('text_body',$campaign->text_body) }}</textarea></label>
<label>Listas<select name="lists[]" multiple size="8" required>@foreach($lists as $list)<option value="{{ $list->id }}" @selected(in_array($list->id, old('lists', $campaign->lists->pluck('id')->all())))>{{ $list->name }}</option>@endforeach</select></label>
<p><button class="btn">Salvar</button></p>
</form>
@endsection
