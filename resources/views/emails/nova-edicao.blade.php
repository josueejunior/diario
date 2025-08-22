<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Edição - Diário Oficial</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #17639D;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #17639D;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .edition-info {
            background: linear-gradient(135deg, #17639D 0%, #1e7bb8 100%);
            color: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .edition-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .edition-date {
            font-size: 18px;
            opacity: 0.9;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 20px 0;
            text-align: center;
        }
        .stat-item {
            flex: 1;
            padding: 15px;
            background: #f8f9fa;
            margin: 0 5px;
            border-radius: 6px;
            border-top: 3px solid #17639D;
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #17639D;
            display: block;
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .materias-preview {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .materia-item {
            padding: 12px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .materia-item:last-child {
            border-bottom: none;
        }
        .materia-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .materia-meta {
            font-size: 12px;
            color: #666;
        }
        .btn {
            display: inline-block;
            background: #17639D;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: bold;
            text-align: center;
        }
        .btn:hover {
            background: #134a73;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .unsubscribe {
            margin-top: 15px;
            font-size: 11px;
        }
        .unsubscribe a {
            color: #999;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
            .stats {
                flex-direction: column;
            }
            .stat-item {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">📰 Diário Oficial</div>
            <div class="subtitle">Prefeitura Municipal</div>
        </div>

        <div class="content">
            <h2>Nova Edição Publicada</h2>
            
            <p>Olá {{ $subscription->name ?: 'Assinante' }},</p>
            
            <p>Uma nova edição do Diário Oficial foi publicada e está disponível para consulta:</p>

            <div class="edition-info">
                <div class="edition-number">Edição {{ $edicao->numero }}</div>
                <div class="edition-date">{{ $edicao->data->format('d \de F \de Y') }}</div>
            </div>

            <div class="stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $edicao->materias->count() }}</span>
                    <span class="stat-label">Matérias</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $edicao->materias->groupBy('tipo_id')->count() }}</span>
                    <span class="stat-label">Tipos</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $edicao->materias->groupBy('orgao_id')->count() }}</span>
                    <span class="stat-label">Órgãos</span>
                </div>
            </div>

            @if($edicao->materias->count() > 0)
            <div class="materias-preview">
                <h4 style="margin-top: 0; color: #17639D;">📋 Principais Matérias desta Edição</h4>
                
                @foreach($edicao->materias->take(5) as $materia)
                <div class="materia-item">
                    <div class="materia-title">{{ $materia->titulo }}</div>
                    <div class="materia-meta">
                        {{ $materia->tipo->nome ?? 'Sem tipo' }} • 
                        {{ $materia->orgao->nome ?? 'Sem órgão' }}
                    </div>
                </div>
                @endforeach
                
                @if($edicao->materias->count() > 5)
                <div style="text-align: center; margin-top: 15px; color: #666; font-size: 12px;">
                    ... e mais {{ $edicao->materias->count() - 5 }} matérias
                </div>
                @endif
            </div>
            @endif

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ route('portal.edicoes.show', $edicao) }}" class="btn">
                    📖 Ver Edição Completa
                </a>
                
                @if($edicao->caminho_arquivo)
                <a href="{{ route('portal.edicoes.pdf', $edicao) }}" class="btn btn-secondary" style="margin-left: 10px;">
                    📄 Download PDF
                </a>
                @endif
            </div>

            @if($edicao->descricao)
            <p style="background: #e7f3ff; padding: 15px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #17639D;">
                <strong>Descrição:</strong><br>
                {{ $edicao->descricao }}
            </p>
            @endif
        </div>

        <div class="footer">
            <p>
                <strong>Diário Oficial - Prefeitura Municipal</strong><br>
                Sistema de Notificações Automáticas
            </p>
            
            <p>
                Este email foi enviado para {{ $subscription->email }} porque você se inscreveu 
                para receber notificações sobre publicações no Diário Oficial.
            </p>
            
            <div class="unsubscribe">
                <a href="{{ $unsubscribe_url }}">Cancelar inscrição</a> | 
                <a href="{{ route('portal.edicoes.index') }}">Ver todas as edições</a>
            </div>
        </div>
    </div>
</body>
</html>
