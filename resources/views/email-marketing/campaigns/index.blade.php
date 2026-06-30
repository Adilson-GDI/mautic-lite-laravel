@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Campanhas</h1>
        <p>Crie, acompanhe e processe os disparos de e-mail marketing.</p>
    </div>
    <div class="actions">
        <a class="btn" href="{{ route('email-marketing.campaigns.create') }}">Nova campanha</a>
        <form method="post" action="{{ route('email-marketing.campaigns.process') }}">
            @csrf
            <button class="btn secondary">Processar pendentes</button>
        </form>
    </div>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nome</th><th>Provedor</th><th>Status</th><th>Mensagens</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($campaigns as $campaign)
                    <tr>
                        <td><a href="{{ route('email-marketing.campaigns.show',$campaign) }}">{{ $campaign->name }}</a></td>
                        <td>{{ $campaign->provider?->name ?? '-' }}</td>
                        <td><span class="status">{{ $campaign->status }}</span></td>
                        <td>{{ $campaign->messages_count }}</td>
                        <td class="actions"><a class="btn secondary small" href="{{ route('email-marketing.campaigns.edit',$campaign) }}">Editar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Nenhuma campanha cadastrada ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pager">{{ $campaigns->links() }}</div>
@endsection
