@extends('layouts.admin')
@section('content')
<div class="page-head">
    <div>
        <h1>Contatos</h1>
        <p>Gerencie assinantes, importações e vinculos com listas.</p>
    </div>
    <div class="actions"><a class="btn" href="{{ route('email-marketing.contacts.create') }}">Novo contato</a></div>
</div>

<div class="card" style="margin-bottom:18px">
    <form method="post" enctype="multipart/form-data" action="{{ route('email-marketing.contacts.import') }}">
        @csrf
        <div class="row">
            <label>CSV nome,email<input type="file" name="csv" required></label>
            <label>ID da lista<input name="list_id"></label>
        </div>
        <p><button class="btn secondary">Importar CSV</button></p>
    </form>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Nome</th><th>E-mail</th><th>Status</th><th>Listas</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td><span class="status">{{ $contact->status }}</span></td>
                        <td>{{ $contact->lists->pluck('name')->join(', ') ?: '-' }}</td>
                        <td class="actions"><a class="btn secondary small" href="{{ route('email-marketing.contacts.edit',$contact) }}">Editar</a><form method="post" action="{{ route('email-marketing.contacts.destroy',$contact) }}">@csrf @method('delete')<button class="btn danger small">Remover</button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Nenhum contato cadastrado ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="pager">{{ $contacts->links() }}</div>
@endsection
