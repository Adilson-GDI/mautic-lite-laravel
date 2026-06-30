<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Entrar - Vox Mautic</title>
    <link href="{{ asset('startbootstrap-sb-admin-2-gh-pages/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('startbootstrap-sb-admin-2-gh-pages/css/sb-admin-2.min.css') }}" rel="stylesheet">
</head>
<body class="bg-gradient-primary">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-lg-6 col-md-8">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-2">Vox Mautic</h1>
                        <p class="text-muted mb-4">Acesse o painel de e-mail marketing.</p>
                    </div>
                    @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
                    <form method="post" action="{{ route('login.store') }}">
                        @csrf
                        <div class="form-group">
                            <input class="form-control form-control-user" type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" required autofocus>
                        </div>
                        <div class="form-group">
                            <input class="form-control form-control-user" type="password" name="password" placeholder="Senha" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember" value="1">
                                <label class="custom-control-label" for="remember">Manter conectado</label>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-user btn-block">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
