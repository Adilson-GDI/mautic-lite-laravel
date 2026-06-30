@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Listas</h1>
        <p>Organize contatos em segmentos para campanhas direcionadas.</p>
    </div>
    <div class="actions"><a class="btn" href="{{ route('email-marketing.lists.create') }}">Nova lista</a></div>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>Nome</th><th>Contatos</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($lists as $list)
                    <tr>
                        <td>{{ $list->id }}</td>
                        <td>{{ $list->name }}</td>
                        <td>{{ $list->contacts_count }}</td>
                        <td><span class="status {{ $list->active ? '' : 'off' }}">{{ $list->active ? 'ativa' : 'inativa' }}</span></td>
                        <td class="actions"><a class="btn secondary small" href="{{ route('email-marketing.lists.edit',$list) }}">Editar</a><form method="post" action="{{ route('email-marketing.lists.destroy',$list) }}">@csrf @method('delete')<button class="btn danger small">Remover</button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Nenhuma lista cadastrada ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pager">{{ $lists->links() }}</div>
@endsection
