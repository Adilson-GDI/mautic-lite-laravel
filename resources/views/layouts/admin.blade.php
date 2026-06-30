<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Vox Mautic' }}</title>

    <link href="{{ asset('startbootstrap-sb-admin-2-gh-pages/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('startbootstrap-sb-admin-2-gh-pages/css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body{font-family:Nunito,Arial,sans-serif}
        .sidebar .nav-item .nav-link span{font-size:.9rem}
        .page-head{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.5rem}
        .page-head h1{font-size:1.85rem;font-weight:800;color:#2e384d;margin:0}
        .page-head p{margin:.45rem 0 0;color:#858796}
        .actions{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
        .btn{border-radius:.35rem;font-weight:700;background:#4e73df;border-color:#4e73df;color:#fff}
        .btn:hover{background:#2e59d9;border-color:#2653d4;color:#fff}
        .btn.secondary{background:#5a5c69;border-color:#5a5c69;color:#fff}
        .btn.secondary:hover{background:#484a54;border-color:#484a54;color:#fff}
        .btn.danger{background:#e74a3b;border-color:#e74a3b;color:#fff}
        .btn.danger:hover{background:#d52a1a;border-color:#d52a1a;color:#fff}
        .btn.small{padding:.25rem .55rem;font-size:.78rem;line-height:1.5}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem}
        .card{border:0;border-radius:.45rem;box-shadow:0 .15rem 1.75rem 0 rgba(58,59,69,.12)}
        .metric{border-left:.25rem solid #4e73df;padding:1rem}
        .metric strong{display:block;font-size:1.8rem;line-height:1;font-weight:800;color:#5a5c69;margin-bottom:.45rem}
        .metric .muted{text-transform:uppercase;letter-spacing:.06em;font-size:.72rem;font-weight:800;color:#4e73df}
        .panel{background:#fff;border-radius:.45rem;box-shadow:0 .15rem 1.75rem 0 rgba(58,59,69,.12);overflow:hidden}
        .table-wrap{overflow-x:auto}
        table{width:100%;margin-bottom:0;color:#5a5c69;background:#fff;border-collapse:collapse}
        th,td{padding:.9rem 1rem;border-bottom:1px solid #e3e6f0;text-align:left;vertical-align:middle}
        th{background:#f8f9fc;color:#4e73df;font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;font-weight:800}
        tr:last-child td{border-bottom:0}
        tbody tr:hover{background:#f8f9fc}
        input,select,textarea{display:block;width:100%;height:auto;padding:.72rem .85rem;border:1px solid #d1d3e2;border-radius:.45rem;color:#5a5c69;background:#fff;transition:border-color .15s ease,box-shadow .15s ease}
        input:focus,select:focus,textarea:focus{border-color:#bac8f3;box-shadow:0 0 0 .2rem rgba(78,115,223,.14);outline:0}
        textarea{min-height:160px;resize:vertical}
        select[multiple]{min-height:220px;padding:.55rem}
        label{font-weight:700;color:#5a5c69}
        .row{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem}
        .form-shell{max-width:1120px}
        .form-card{background:#fff;border:0;border-radius:.65rem;box-shadow:0 .15rem 1.75rem 0 rgba(58,59,69,.12);margin-bottom:1.25rem;overflow:hidden}
        .form-card-header{padding:1rem 1.25rem;border-bottom:1px solid #e3e6f0;background:#fff}
        .form-card-header h2{font-size:1rem;font-weight:800;color:#4e73df;margin:0}
        .form-card-header p{margin:.35rem 0 0;color:#858796;font-size:.88rem}
        .form-card-body{padding:1.25rem}
        .form-grid{display:grid;grid-template-columns:repeat(12,1fr);gap:1rem}
        .form-field{grid-column:span 6}
        .form-field.full{grid-column:1/-1}
        .form-field.third{grid-column:span 4}
        .form-field.quarter{grid-column:span 3}
        .form-field label,.form-label{display:block;margin-bottom:.4rem;font-size:.78rem;font-weight:800;color:#4a5568;text-transform:uppercase;letter-spacing:.04em}
        .form-help{display:block;margin-top:.4rem;color:#858796;font-size:.82rem}
        .form-actions{display:flex;align-items:center;gap:.75rem;margin-top:1.25rem}
        .switch-line{display:flex;align-items:center;gap:.65rem;padding:.85rem 1rem;border:1px solid #e3e6f0;border-radius:.55rem;background:#f8f9fc;color:#5a5c69;font-weight:800}
        .switch-line input{width:1rem;height:1rem}
        .alert{border-radius:.35rem}
        .ok{background:#eafaf1;border-color:#c7f0d8;color:#1f7a45}
        .err{background:#fff1f1;border-color:#f5c6cb;color:#9d1c13}
        .muted{color:#858796;font-size:.88rem}
        .field{margin-bottom:1rem}
        .status{display:inline-flex;align-items:center;border-radius:10rem;padding:.28rem .58rem;background:#e8f4ff;color:#2e59d9;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em}
        .status.off{background:#eaecf4;color:#6e707e}
        .empty{padding:2rem;text-align:center;color:#858796}
        .pager{margin-top:1rem}
        .pagination{margin-bottom:0}
        @media (max-width:768px){
            .page-head{display:block}
            .page-head .actions{margin-top:1rem}
            .page-head h1{font-size:1.55rem}
            .form-field,.form-field.third,.form-field.quarter{grid-column:1/-1}
        }
    </style>
    @stack('styles')
</head>
<body id="page-top">
<div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('email-marketing.dashboard') }}">
            <div class="sidebar-brand-icon">
                <i class="fas fa-paper-plane"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Vox Mautic</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item {{ request()->routeIs('email-marketing.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('email-marketing.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Marketing</div>

        <li class="nav-item {{ request()->routeIs('email-marketing.campaigns.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('email-marketing.campaigns.index') }}">
                <i class="fas fa-fw fa-bullhorn"></i>
                <span>Campanhas</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('email-marketing.contacts.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('email-marketing.contacts.index') }}">
                <i class="fas fa-fw fa-address-book"></i>
                <span>Contatos</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('email-marketing.lists.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('email-marketing.lists.index') }}">
                <i class="fas fa-fw fa-list"></i>
                <span>Listas</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('email-marketing.providers.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('email-marketing.providers.index') }}">
                <i class="fas fa-fw fa-server"></i>
                <span>Provedores</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <div class="sidebar-heading">Sistema</div>

        <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Usuarios</span>
            </a>
        </li>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>

    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <div class="d-none d-sm-inline-block text-gray-700 font-weight-bold">
                    Painel de e-mail marketing
                </div>

                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name }}</span>
                            <i class="fas fa-user-circle fa-lg text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Perfil</a>
                            <div class="dropdown-divider"></div>
                            <form method="post" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Sair</button>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link text-gray-600 small">{{ now()->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </nav>

            <div class="container-fluid">
                @if(session('status'))<div class="alert ok">{{ session('status') }}</div>@endif
                @if(session('error'))<div class="alert err">{{ session('error') }}</div>@endif
                @if($errors->any())<div class="alert err">{{ $errors->first() }}</div>@endif
                @yield('content')
            </div>
        </div>

        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Vox Mautic</span>
                </div>
            </div>
        </footer>
    </div>
</div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<script src="{{ asset('startbootstrap-sb-admin-2-gh-pages/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('startbootstrap-sb-admin-2-gh-pages/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('startbootstrap-sb-admin-2-gh-pages/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('startbootstrap-sb-admin-2-gh-pages/js/sb-admin-2.min.js') }}"></script>
@stack('scripts')
</body>
</html>
