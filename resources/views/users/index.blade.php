@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Usuarios</h1>
        <p>Gerencie os acessos ao painel.</p>
    </div>
    <div class="actions"><a class="btn" href="{{ route('users.create') }}">Novo usuario</a></div>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Nome</th><th>E-mail</th><th>Criado em</th><th></th></tr></thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="actions">
                            <a class="btn secondary small" href="{{ route('users.edit', $user) }}">Editar</a>
                            @if(! auth()->user()->is($user))
                                <form method="post" action="{{ route('users.destroy', $user) }}">@csrf @method('delete')<button class="btn danger small">Remover</button></form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="empty">Nenhum usuario cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pager">{{ $users->links() }}</div>
@endsection
