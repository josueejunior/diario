<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Diário Oficial') | {{ config('app.name', 'Sistema') }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css">

    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('images/logo-preloader.png') }}" alt="Logo" height="60" width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="fas fa-eye mr-1"></i> Ver Portal
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            <li class="nav-item">
                <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                </a>
                <div class="navbar-search-block">
                    <form class="form-inline" action="{{ route('admin.search') }}" method="GET">
                        <div class="input-group input-group-sm">
                            <input class="form-control form-control-navbar" type="search" placeholder="Buscar..." aria-label="Search" name="q">
                            <div class="input-group-append">
                                <button class="btn btn-navbar" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </li>

            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-comments"></i>
                    <span class="badge badge-danger navbar-badge">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="#" class="dropdown-item">
                        <!-- Message Start -->
                        <div class="media">
                            <img src="{{ asset('images/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    Brad Diesel
                                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">Call me whenever you can...</p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                            </div>
                        </div>
                        <!-- Message End -->
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                </div>
            </li>

            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>

            <!-- User Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" 
                         class="img-circle" style="width: 30px; height: 30px;" alt="User Image">
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <div class="dropdown-header">
                        <strong>{{ Auth::user()->name }}</strong><br>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user mr-2"></i> Meu Perfil
                    </a>
                    <a href="{{ route('admin.configuracoes.index') }}" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Configurações
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Sair
                        </a>
                    </form>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Diário Oficial</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" 
                         class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="{{ route('admin.profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Buscar" aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <!-- Diário Oficial -->
                    <li class="nav-item {{ request()->routeIs(['admin.edicoes.*', 'admin.materias.*', 'admin.tipos.*', 'admin.orgaos.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.edicoes.*', 'admin.materias.*', 'admin.tipos.*', 'admin.orgaos.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>
                                Diário Oficial
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.edicoes.index') }}" class="nav-link {{ request()->routeIs('admin.edicoes.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Edições</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.materias.index') }}" class="nav-link {{ request()->routeIs('admin.materias.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Matérias</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.tipos.index') }}" class="nav-link {{ request()->routeIs('admin.tipos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tipos de Matéria</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.orgaos.index') }}" class="nav-link {{ request()->routeIs('admin.orgaos.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Órgãos</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Assinatura Digital -->
                    <li class="nav-item {{ request()->routeIs(['admin.assinatura.*', 'admin.certificados.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.assinatura.*', 'admin.certificados.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-certificate"></i>
                            <p>
                                Assinatura Digital
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.assinatura.dashboard') }}" class="nav-link {{ request()->routeIs('admin.assinatura.dashboard') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.certificados.index') }}" class="nav-link {{ request()->routeIs('admin.certificados.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Certificados</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.assinatura.validacao') }}" class="nav-link {{ request()->routeIs('admin.assinatura.validacao') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Validação</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Notificações & Webhooks -->
                    <li class="nav-item {{ request()->routeIs(['admin.subscriptions.*', 'admin.webhooks.*', 'admin.notifications.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.subscriptions.*', 'admin.webhooks.*', 'admin.notifications.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bell"></i>
                            <p>
                                Notificações
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.subscriptions.index') }}" class="nav-link {{ request()->routeIs('admin.subscriptions.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Inscrições</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.webhooks.index') }}" class="nav-link {{ request()->routeIs('admin.webhooks.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Webhooks</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Histórico</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Workflow & Aprovação -->
                    <li class="nav-item {{ request()->routeIs(['admin.workflow.*', 'admin.approval.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.workflow.*', 'admin.approval.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tasks"></i>
                            <p>
                                Workflow
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.approval.pending') }}" class="nav-link {{ request()->routeIs('admin.approval.pending') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendências</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.workflow.index') }}" class="nav-link {{ request()->routeIs('admin.workflow.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Configurar Fluxo</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.approval.history') }}" class="nav-link {{ request()->routeIs('admin.approval.history') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Histórico</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Relatórios -->
                    <li class="nav-item {{ request()->routeIs(['admin.relatorios.*', 'admin.audit.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.relatorios.*', 'admin.audit.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Relatórios
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.relatorios.index') }}" class="nav-link {{ request()->routeIs('admin.relatorios.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.relatorios.publicacoes') }}" class="nav-link {{ request()->routeIs('admin.relatorios.publicacoes') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Publicações</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.relatorios.visualizacoes') }}" class="nav-link {{ request()->routeIs('admin.relatorios.visualizacoes') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Visualizações</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.relatorios.downloads') }}" class="nav-link {{ request()->routeIs('admin.relatorios.downloads') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Downloads</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.audit.dashboard') }}" class="nav-link {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Auditoria</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Usuários & Permissões -->
                    <li class="nav-item {{ request()->routeIs(['admin.users.*', 'admin.roles.*', 'admin.permissions.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.users.*', 'admin.roles.*', 'admin.permissions.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Usuários
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Usuários</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Perfis</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Permissões</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Configurações -->
                    <li class="nav-item {{ request()->routeIs(['admin.configuracoes.*', 'admin.sistema.*', 'admin.backup.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.configuracoes.*', 'admin.sistema.*', 'admin.backup.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Configurações
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.configuracoes.geral') }}" class="nav-link {{ request()->routeIs('admin.configuracoes.geral') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Geral</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.configuracoes.email') }}" class="nav-link {{ request()->routeIs('admin.configuracoes.email') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>E-mail</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.configuracoes.whatsapp') }}" class="nav-link {{ request()->routeIs('admin.configuracoes.whatsapp') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>WhatsApp</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.sistema.info') }}" class="nav-link {{ request()->routeIs('admin.sistema.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Sistema</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.backup.index') }}" class="nav-link {{ request()->routeIs('admin.backup.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Backup</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Ferramentas -->
                    <li class="nav-item {{ request()->routeIs(['admin.ferramentas.*', 'admin.migration.*', 'admin.search.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.ferramentas.*', 'admin.migration.*', 'admin.search.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>
                                Ferramentas
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.search.advanced') }}" class="nav-link {{ request()->routeIs('admin.search.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Busca Avançada</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.migration.dashboard') }}" class="nav-link {{ request()->routeIs('admin.migration.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Migração/OCR</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ferramentas.observability') }}" class="nav-link {{ request()->routeIs('admin.ferramentas.observability') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Observabilidade</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.ferramentas.accessibility') }}" class="nav-link {{ request()->routeIs('admin.ferramentas.accessibility') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Acessibilidade</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- API & Dados Abertos -->
                    <li class="nav-item {{ request()->routeIs(['admin.api.*', 'admin.opendata.*']) ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs(['admin.api.*', 'admin.opendata.*']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-database"></i>
                            <p>
                                API & Dados
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.api.dashboard') }}" class="nav-link {{ request()->routeIs('admin.api.dashboard') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Dashboard API</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.api.tokens') }}" class="nav-link {{ request()->routeIs('admin.api.tokens') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Tokens de Acesso</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.opendata.catalog') }}" class="nav-link {{ request()->routeIs('admin.opendata.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Catálogo Aberto</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Sucesso!</h5>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Erro!</h5>
                    {{ session('error') }}
                </div>
                @endif

                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Atenção!</h5>
                    {{ session('warning') }}
                </div>
                @endif

                @if(session('info'))
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-info"></i> Informação!</h5>
                    {{ session('info') }}
                </div>
                @endif

                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name') }}</a>.</strong>
        Todos os direitos reservados.
        <div class="float-right d-none d-sm-inline-block">
            <b>Versão</b> 3.2.0
        </div>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<!-- Sparkline -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
<!-- JQVMap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Knob/1.2.13/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.1.2/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

@stack('scripts')
</body>
</html>
