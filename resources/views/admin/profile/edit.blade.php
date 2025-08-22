@extends('layouts.adminlte')

@section('title', 'Editar Perfil')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Editar Perfil</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Perfil</li>
                </ol>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <!-- Informações do Perfil -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user mr-2"></i>
                            Informações Pessoais
                        </h3>
                    </div>
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nome Completo</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <h5><i class="fas fa-lock mr-2"></i>Alterar Senha</h5>
                            <p class="text-muted">Deixe em branco se não quiser alterar a senha.</p>

                            <div class="form-group">
                                <label for="password">Nova Senha</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmar Nova Senha</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                Salvar Alterações
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Informações da Conta -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informações da Conta
                        </h3>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-4">ID:</dt>
                            <dd class="col-sm-8">{{ $user->id }}</dd>

                            <dt class="col-sm-4">Criado em:</dt>
                            <dd class="col-sm-8">{{ $user->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-4">Último login:</dt>
                            <dd class="col-sm-8">
                                @if($user->updated_at)
                                    {{ $user->updated_at->format('d/m/Y H:i') }}
                                @else
                                    Nunca
                                @endif
                            </dd>

                            <dt class="col-sm-4">E-mail verificado:</dt>
                            <dd class="col-sm-8">
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Verificado
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Não verificado
                                    </span>
                                @endif
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- Atividade Recente -->
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-2"></i>
                            Atividade Recente
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="time-label">
                                <span class="bg-red">Últimas 24 horas</span>
                            </div>
                            <div>
                                <i class="fas fa-user bg-blue"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="far fa-clock"></i> Agora</span>
                                    <h3 class="timeline-header">Editando perfil</h3>
                                    <div class="timeline-body">
                                        Você está visualizando e editando suas informações de perfil.
                                    </div>
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
        .timeline {
            position: relative;
            margin: 0 0 30px 0;
            padding: 0;
            list-style: none;
        }
        .timeline:before {
            content: '';
            position: absolute;
            top: 0;
            left: 25px;
            bottom: 0;
            width: 4px;
            background: #ddd;
        }
        .timeline > li {
            position: relative;
            margin-right: 10px;
            margin-bottom: 15px;
        }
        .timeline > li:before,
        .timeline > li:after {
            content: " ";
            display: table;
        }
        .timeline > li:after {
            clear: both;
        }
        .timeline > li > .timeline-item {
            -webkit-box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            border-radius: 3px;
            margin-top: 0;
            background: #fff;
            color: #444;
            margin-left: 55px;
            margin-right: 15px;
            padding: 0;
            position: relative;
        }
        .timeline > li > .fa,
        .timeline > li > .fas,
        .timeline > li > .far,
        .timeline > li > .fab,
        .timeline > li > .fal,
        .timeline > li > .fad,
        .timeline > li > .svg-inline--fa {
            width: 30px;
            height: 30px;
            font-size: 15px;
            line-height: 30px;
            position: absolute;
            color: #666;
            background: #d2d6de;
            border-radius: 50%;
            text-align: center;
            left: 18px;
            top: 0;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@stop
