<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Configurar acesso - Vox Mautic</title>
    <link href="{{ asset('startbootstrap-sb-admin-2-gh-pages/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-2">Criar primeiro usuario</h1>
                        <p class="text-muted mb-4">Esse acesso sera usado para administrar o painel.</p>
                    </div>
                    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                    <form method="post" action="{{ route('setup.store') }}">
                        @csrf
                        <div class="form-group"><input class="form-control form-control-user" name="name" value="{{ old('name') }}" placeholder="Nome" required autofocus></div>
                        <div class="form-group"><input class="form-control form-control-user" type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" required></div>
                        <div class="form-group"><input class="form-control form-control-user" type="password" name="password" placeholder="Senha" required></div>
                        <div class="form-group"><input class="form-control form-control-user" type="password" name="password_confirmation" placeholder="Confirmar senha" required></div>
                        <button class="btn btn-primary btn-user btn-block">Criar acesso</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
