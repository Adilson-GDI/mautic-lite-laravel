@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>{{ $campaign->name }}</h1>
        <p>Provedor: {{ $campaign->provider->name }} | Status: {{ $campaign->status }} | Listas: {{ $campaign->lists->pluck('name')->join(', ') }}</p>
    </div>
    <div class="actions">
        <a class="btn secondary" href="{{ route('email-marketing.campaigns.edit',$campaign) }}">Editar</a>
        <form method="post" action="{{ route('email-marketing.campaigns.start',$campaign) }}">@csrf<button class="btn">Iniciar</button></form>
        <form method="post" action="{{ route('email-marketing.campaigns.pause',$campaign) }}">@csrf<button class="btn secondary">Pausar</button></form>
        <form method="post" action="{{ route('email-marketing.campaigns.cancel',$campaign) }}">@csrf<button class="btn danger">Cancelar</button></form>
    </div>
</div>
<div class="grid">
@foreach(['pending','processing','sent','opened','clicked','bounced','failed','unsubscribed','canceled'] as $status)
    <div class="card metric"><strong>{{ $stats[$status] ?? 0 }}</strong><span class="muted">{{ $status }}</span></div>
@endforeach
    <div class="card metric"><strong>{{ $openRate }}%</strong><span class="muted">abertura</span></div>
    <div class="card metric"><strong>{{ $clickRate }}%</strong><span class="muted">clique</span></div>
    <div class="card metric"><strong>{{ $errorRate }}%</strong><span class="muted">erro</span></div>
</div>
<div class="card"><h2>HTML</h2>{!! $campaign->html_body !!}</div>
@endsection
