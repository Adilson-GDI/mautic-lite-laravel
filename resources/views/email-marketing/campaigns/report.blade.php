@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Relatorio</h1>
        <p>{{ $campaign->name }} | {{ $campaign->provider?->name }}</p>
    </div>
    <div class="actions">
        <a class="btn secondary" href="{{ route('email-marketing.campaigns.show', $campaign) }}">Voltar</a>
    </div>
</div>

<div class="grid">
    <div class="card metric"><strong>{{ $total }}</strong><span class="muted">mensagens</span></div>
    <div class="card metric"><strong>{{ $sent }}</strong><span class="muted">enviadas</span></div>
    <div class="card metric"><strong>{{ $eventStats['opened'] ?? 0 }}</strong><span class="muted">aberturas</span></div>
    <div class="card metric"><strong>{{ $eventStats['clicked'] ?? 0 }}</strong><span class="muted">cliques</span></div>
    <div class="card metric"><strong>{{ $openRate }}%</strong><span class="muted">taxa abertura</span></div>
    <div class="card metric"><strong>{{ $clickRate }}%</strong><span class="muted">taxa clique</span></div>
</div>

<h2>Links mais clicados</h2>
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>URL</th><th>Cliques</th></tr></thead>
            <tbody>
                @forelse($topLinks as $link)
                    <tr><td><a href="{{ $link->url }}" target="_blank" rel="noopener">{{ $link->url }}</a></td><td>{{ $link->total }}</td></tr>
                @empty
                    <tr><td colspan="2" class="empty">Nenhum clique registrado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<h2>Eventos recentes</h2>
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Evento</th><th>Contato</th><th>URL</th><th>IP</th><th>Data</th></tr></thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td><span class="status">{{ $event->event_type }}</span></td>
                        <td>{{ $event->message?->contact?->email ?? $event->message?->to_email }}</td>
                        <td>{{ $event->url ?: '-' }}</td>
                        <td>{{ $event->ip ?: '-' }}</td>
                        <td>{{ $event->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Nenhum evento registrado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pager">{{ $events->links() }}</div>

<h2>Mensagens</h2>
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Contato</th><th>Status</th><th>Enviado</th><th>Aberto</th><th>Clicado</th><th>Erro</th></tr></thead>
            <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>{{ $message->contact?->email ?? $message->to_email }}</td>
                        <td><span class="status">{{ $message->status }}</span></td>
                        <td>{{ $message->sent_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $message->opened_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $message->clicked_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $message->error_message ?: '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Nenhuma mensagem gerada ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pager">{{ $messages->links() }}</div>
@endsection
