@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Provedores</h1>
        <p>Configure remetentes, autenticação e limites de envio.</p>
    </div>
    <div class="actions"><a class="btn" href="{{ route('email-marketing.providers.create') }}">Novo provedor</a></div>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nome</th><th>Tipo</th><th>Remetente</th><th>Limites</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($providers as $provider)
                    <tr>
                        <td>{{ $provider->name }}</td>
                        <td>{{ $provider->type }}</td>
                        <td>{{ $provider->from_email }}</td>
                        <td>{{ $provider->hourly_limit }}/h, {{ $provider->daily_limit }}/dia</td>
                        <td><span class="status {{ $provider->active ? '' : 'off' }}">{{ $provider->active ? 'ativo' : 'inativo' }}</span></td>
                        <td class="actions">
                            <a class="btn secondary small" href="{{ route('email-marketing.providers.edit', $provider) }}">Editar</a>
                            <form method="post" action="{{ route('email-marketing.providers.destroy', $provider) }}">@csrf @method('delete')<button class="btn danger small">Remover</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="empty">Nenhum provedor cadastrado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pager">{{ $providers->links() }}</div>
@endsection
