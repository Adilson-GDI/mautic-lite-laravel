@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Dashboard de e-mails</h1>
        <p>Visão geral da operação, volume de contatos e status das mensagens.</p>
    </div>
</div>
<div class="grid">
    <div class="card metric"><strong>{{ $providers }}</strong><span class="muted">provedores</span></div>
    <div class="card metric"><strong>{{ $contacts }}</strong><span class="muted">contatos</span></div>
    @foreach(['pending','sent','opened','clicked','failed','bounced'] as $status)
        <div class="card metric"><strong>{{ $messageStats[$status] ?? 0 }}</strong><span class="muted">{{ $status }}</span></div>
    @endforeach
</div>
<h2>Campanhas recentes</h2>
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nome</th><th>Status</th><th>Criada</th></tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td><a href="{{ route('email-marketing.campaigns.show', $campaign) }}">{{ $campaign->name }}</a></td>
                        <td><span class="status">{{ $campaign->status }}</span></td>
                        <td>{{ $campaign->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="empty">Nenhuma campanha recente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
