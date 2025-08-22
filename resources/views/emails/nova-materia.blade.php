<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Publicação - Diário Oficial</title>
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
        .document-info {
            background: #f8f9fa;
            border-left: 4px solid #17639D;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .document-info h3 {
            margin: 0 0 15px 0;
            color: #17639D;
            font-size: 18px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            min-width: 80px;
            margin-right: 10px;
        }
        .info-value {
            color: #333;
            flex: 1;
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
            .info-row {
                flex-direction: column;
            }
            .info-label {
                min-width: auto;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏛️ Diário Oficial</div>
            <div class="subtitle">Prefeitura Municipal</div>
        </div>

        <div class="content">
            <h2>Nova Publicação Disponível</h2>
            
            <p>Olá {{ $subscription->name ?: 'Assinante' }},</p>
            
            <p>Uma nova matéria foi publicada no Diário Oficial e corresponde aos seus critérios de interesse:</p>

            <div class="document-info">
                <h3>{{ $materia->titulo }}</h3>
                
                <div class="info-row">
                    <span class="info-label">Tipo:</span>
                    <span class="info-value">{{ $materia->tipo->nome }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Órgão:</span>
                    <span class="info-value">{{ $materia->orgao->nome }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Data:</span>
                    <span class="info-value">{{ $materia->data->format('d/m/Y') }}</span>
                </div>
                
                @if($materia->numero)
                <div class="info-row">
                    <span class="info-label">Número:</span>
                    <span class="info-value">{{ $materia->numero }}</span>
                </div>
                @endif
            </div>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ route('portal.materias.show', $materia) }}" class="btn">
                    📄 Ler Matéria Completa
                </a>
            </div>

            <p style="margin-top: 25px;">
                <strong>Resumo:</strong><br>
                {{ Str::limit(strip_tags($materia->texto), 200) }}
            </p>
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
                <a href="{{ route('portal.materias.index') }}">Ver todas as matérias</a>
            </div>
        </div>
    </div>
</body>
</html>

