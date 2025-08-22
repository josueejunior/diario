<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirme sua inscrição - Diário Oficial</title>
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
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 14px;
        }
        .content {
            margin-bottom: 30px;
        }
        .verification-box {
            background: linear-gradient(135deg, #28a745 0%, #34ce57 100%);
            color: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .verification-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            background: white;
            color: #28a745;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 25px;
            margin: 15px 0;
            font-weight: bold;
            text-align: center;
            border: 2px solid white;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: transparent;
            color: white;
        }
        .info-section {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            position: relative;
            padding-left: 25px;
        }
        .feature-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 12px;
            margin: 15px 0;
            font-size: 13px;
            color: #856404;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔔 Notificações</div>
            <div class="subtitle">Diário Oficial - Prefeitura Municipal</div>
        </div>

        <div class="content">
            <h2>Confirme sua Inscrição</h2>
            
            <p>Olá {{ $subscription->name ?: 'Assinante' }},</p>
            
            <p>Recebemos sua solicitação para receber notificações automáticas sobre publicações no Diário Oficial. Para confirmar sua inscrição e começar a receber as notificações, clique no botão abaixo:</p>

            <div class="verification-box">
                <div class="verification-icon">✉️</div>
                <h3 style="margin: 0 0 15px 0;">Confirmar Inscrição</h3>
                <p style="margin: 0 0 20px 0; opacity: 0.9;">
                    Clique no botão para ativar suas notificações
                </p>
                <a href="{{ $verify_url }}" class="btn">
                    ✓ Confirmar Inscrição
                </a>
            </div>

            <div class="info-section">
                <h4 style="color: #17639D; margin-top: 0;">📋 O que você vai receber:</h4>
                <ul class="feature-list">
                    <li>Notificações de novas publicações no Diário Oficial</li>
                    <li>Alertas sobre edições especiais e extraordinárias</li>
                    <li>Resumos das principais matérias</li>
                    <li>Links diretos para acessar os documentos</li>
                    @if($subscription->type === 'whatsapp' || $subscription->type === 'both')
                    <li>Mensagens via WhatsApp para atualizações importantes</li>
                    @endif
                </ul>
            </div>

            <div class="info-section">
                <h4 style="color: #17639D; margin-top: 0;">⚙️ Suas Preferências:</h4>
                <p><strong>Email:</strong> {{ $subscription->email }}</p>
                @if($subscription->phone)
                <p><strong>WhatsApp:</strong> {{ $subscription->phone }}</p>
                @endif
                <p><strong>Tipo de Notificação:</strong> 
                    @switch($subscription->type)
                        @case('email')
                            Apenas Email
                            @break
                        @case('whatsapp')
                            Apenas WhatsApp
                            @break
                        @case('both')
                            Email e WhatsApp
                            @break
                    @endswitch
                </p>
                
                @if($subscription->preferences)
                <div style="margin-top: 15px;">
                    <strong>Filtros Personalizados:</strong>
                    @if(!empty($subscription->preferences['tipos']))
                        <p style="margin: 5px 0; font-size: 13px;">• Tipos de documento específicos</p>
                    @endif
                    @if(!empty($subscription->preferences['orgaos']))
                        <p style="margin: 5px 0; font-size: 13px;">• Órgãos específicos</p>
                    @endif
                    @if(!empty($subscription->preferences['keywords']))
                        <p style="margin: 5px 0; font-size: 13px;">• Palavras-chave: {{ implode(', ', $subscription->preferences['keywords']) }}</p>
                    @endif
                </div>
                @endif
            </div>

            <div class="warning">
                <strong>⚠️ Importante:</strong> Este link de confirmação expira em 48 horas. Se você não confirmou sua inscrição dentro deste prazo, será necessário se inscrever novamente.
            </div>

            <p style="margin-top: 25px;">
                Se você não solicitou esta inscrição, pode ignorar este email com segurança.
            </p>
        </div>

        <div class="footer">
            <p>
                <strong>Diário Oficial - Prefeitura Municipal</strong><br>
                Sistema de Notificações Automáticas
            </p>
            
            <p>
                Este email foi enviado para {{ $subscription->email }} para confirmação de inscrição.
            </p>
            
            <p style="margin-top: 15px; font-size: 11px;">
                <a href="{{ route('home') }}" style="color: #999;">Acessar o portal</a> | 
                <a href="{{ route('portal.edicoes.index') }}" style="color: #999;">Ver edições</a>
            </p>
        </div>
    </div>
</body>
</html>
