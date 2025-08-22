<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link de Gerenciamento - Diário Oficial</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 300;
        }
        .content {
            padding: 40px 30px;
        }
        .icon {
            width: 60px;
            height: 60px;
            background: #667eea;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .text-center {
            text-align: center;
        }
        .small-text {
            font-size: 14px;
            color: #666;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Gerenciar Notificações</h1>
        </div>
        
        <div class="content">
            <div class="icon">⚙️</div>
            
            <h2 class="text-center">Link de Gerenciamento</h2>
            
            <p>Olá!</p>
            
            <p>Você solicitou um link para gerenciar suas preferências de notificações do <strong>Diário Oficial</strong>.</p>
            
            <div class="info-box">
                <strong>📧 Email:</strong> {{ $subscription->email }}<br>
                <strong>📅 Inscrito em:</strong> {{ $subscription->created_at->format('d/m/Y H:i') }}
            </div>
            
            <div class="text-center">
                <a href="{{ route('portal.subscriptions.manage', $subscription->management_token) }}" class="btn">
                    🔧 Gerenciar Inscrição
                </a>
            </div>
            
            <div class="small-text">
                <p><strong>O que você pode fazer:</strong></p>
                <ul>
                    <li>✏️ Alterar suas preferências de notificação</li>
                    <li>📱 Adicionar ou alterar número do WhatsApp</li>
                    <li>🏷️ Escolher tipos de documentos específicos</li>
                    <li>🔍 Definir palavras-chave de interesse</li>
                    <li>⏸️ Pausar ou reativar notificações</li>
                    <li>❌ Cancelar inscrição</li>
                </ul>
            </div>
            
            <div class="info-box">
                <strong>🔒 Segurança:</strong> Este link é único e pessoal. Não o compartilhe com terceiros.
            </div>
            
            <p class="small-text">
                <strong>Precisa de ajuda?</strong><br>
                Entre em contato conosco através do email: suporte@diario.gov.br
            </p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Diário Oficial - Todos os direitos reservados</p>
            <p>Esta é uma mensagem automática, não responda este email.</p>
        </div>
    </div>
</body>
</html>
