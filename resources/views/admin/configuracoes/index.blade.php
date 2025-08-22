@extends('layouts.adminlte')

@section('title', 'Configurações do Sistema')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-cogs"></i> Configurações</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Configurações</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Configurações Gerais -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3><i class="fas fa-building"></i></h3>
                    <p>Configurações Gerais</p>
                </div>
                <div class="icon">
                    <i class="fas fa-building"></i>
                </div>
                <a href="{{ route('admin.configuracoes.geral') }}" class="small-box-footer">
                    Configurar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Configurações de E-mail -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3><i class="fas fa-envelope"></i></h3>
                    <p>E-mail</p>
                </div>
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <a href="{{ route('admin.configuracoes.email') }}" class="small-box-footer">
                    Configurar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Configurações do WhatsApp -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3><i class="fab fa-whatsapp"></i></h3>
                    <p>WhatsApp</p>
                </div>
                <div class="icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <a href="{{ route('admin.configuracoes.whatsapp') }}" class="small-box-footer">
                    Configurar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <!-- Backup e Manutenção -->
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3><i class="fas fa-hdd"></i></h3>
                    <p>Backup</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hdd"></i>
                </div>
                <a href="{{ route('admin.backup.index') }}" class="small-box-footer">
                    Gerenciar <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Configurações do Sistema -->
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-server"></i> Sistema</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-database"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Cache</span>
                                    <span class="info-box-number">
                                        <form action="{{ route('admin.sistema.cache.clear') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-trash"></i> Limpar
                                            </button>
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-rocket"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Otimização</span>
                                    <span class="info-box-number">
                                        <form action="{{ route('admin.configuracoes.otimizar') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-tachometer-alt"></i> Otimizar
                                            </button>
                                        </form>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informações do Servidor -->
        <div class="col-md-6">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-info-circle"></i> Informações do Servidor</h3>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-4">PHP:</dt>
                        <dd class="col-sm-8">{{ PHP_VERSION }}</dd>
                        
                        <dt class="col-sm-4">Laravel:</dt>
                        <dd class="col-sm-8">{{ app()->version() }}</dd>
                        
                        <dt class="col-sm-4">Servidor:</dt>
                        <dd class="col-sm-8">{{ $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-4">Banco:</dt>
                        <dd class="col-sm-8">MySQL {{ DB::select('SELECT VERSION() as version')[0]->version ?? 'N/A' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Configurações Avançadas -->
        <div class="col-md-12">
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cog"></i> Configurações Avançadas</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Logs do Sistema -->
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-file-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Logs</span>
                                    <span class="info-box-number">
                                        <a href="#" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-eye"></i> Visualizar
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Certificados Digitais -->
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-certificate"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Certificados</span>
                                    <span class="info-box-number">
                                        <a href="#" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-cog"></i> Gerenciar
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- API Tokens -->
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-purple"><i class="fas fa-key"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">API Tokens</span>
                                    <span class="info-box-number">
                                        <a href="#" class="btn btn-sm btn-outline-purple">
                                            <i class="fas fa-plus"></i> Gerenciar
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Monitoramento -->
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Monitor</span>
                                    <span class="info-box-number">
                                        <a href="#" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-eye"></i> Acessar
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .info-box-number .btn {
            padding: 2px 8px;
            font-size: 11px;
        }
        .small-box .icon {
            font-size: 70px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Confirmação para ações de sistema
            $('form[action*="cache"], form[action*="otimizar"]').on('submit', function(e) {
                const action = $(this).attr('action').includes('cache') ? 'limpar o cache' : 'otimizar o sistema';
                if (!confirm(`Tem certeza que deseja ${action}?`)) {
                    e.preventDefault();
                }
            });
        });
    </script>
@stop
