<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição Nº {{ $edicao->numero }} - Diário Oficial</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
        }
        .edicao-info {
            margin-bottom: 30px;
            border: 1px solid #ccc;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .edicao-info h3 {
            margin-top: 0;
            font-size: 16px;
        }
        .edicao-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        .materias-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        .materia {
            margin-bottom: 30px;
        }
        .materia h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .materia .info {
            font-size: 12px;
            color: #555;
            margin-bottom: 10px;
        }
        .materia .content {
            font-size: 14px;
            text-align: justify;
        }
        .footer {
            margin-top: 50px;
            font-size: 12px;
            text-align: center;
            border-top: 1px solid #333;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        .autenticacao {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            padding: 10px;
            border: 1px dashed #333;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DIÁRIO OFICIAL</h1>
        <h2>Edição Nº {{ $edicao->numero }} - {{ $edicao->data_publicacao->format('d/m/Y') }}</h2>
        <p>{{ $edicao->tipo === 'extra' ? 'EDIÇÃO EXTRA' : 'EDIÇÃO NORMAL' }}</p>
    </div>

    <div class="edicao-info">
        <h3>INFORMAÇÕES DA EDIÇÃO</h3>
        <p><strong>Número:</strong> {{ $edicao->numero }}</p>
        <p><strong>Data de Publicação:</strong> {{ $edicao->data_publicacao->format('d/m/Y') }}</p>
        @if($edicao->descricao)
            <p><strong>Descrição:</strong> {{ $edicao->descricao }}</p>
        @endif
    </div>

    <h3 class="materias-title">CONTEÚDO DA EDIÇÃO</h3>

    @forelse($edicao->materias as $materia)
        <div class="materia">
            <h4>{{ $materia->titulo }}</h4>
            <div class="info">
                <strong>{{ $materia->tipo->nome ?? 'Sem tipo' }}</strong> | 
                <strong>Órgão:</strong> {{ $materia->orgao->nome ?? 'Não especificado' }} | 
                <strong>Número:</strong> {{ $materia->numero }}
            </div>
            <div class="content">
                {!! nl2br(e($materia->texto)) !!}
            </div>
        </div>
        
        @if(!$loop->last)
            <hr>
        @endif
    @empty
        <p>Esta edição não possui matérias.</p>
    @endforelse

    <div class="autenticacao">
        <p><strong>CÓDIGO DE AUTENTICIDADE:</strong> {{ $edicao->hash }}</p>
        <p>A autenticidade deste documento pode ser verificada no site oficial, utilizando o código acima.</p>
    </div>

    <div class="footer">
        <p>Diário Oficial - Data da geração: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Documento assinado digitalmente conforme legislação vigente.</p>
    </div>
</body>
</html>
