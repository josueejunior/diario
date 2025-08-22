<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Edicao;
use App\Models\Materia;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RSSController extends Controller
{
    /**
     * Feed RSS das edições mais recentes
     */
    public function edicoes(Request $request)
    {
        $limit = $request->get('limit', 10);
        $edicoes = Edicao::with(['materias'])
            ->where('publicado', true)
            ->orderBy('data', 'desc')
            ->limit($limit)
            ->get();

        $rss = $this->generateRSSHeader('Edições do Diário Oficial', 'Últimas edições publicadas no Diário Oficial');
        
        foreach ($edicoes as $edicao) {
            $link = route('portal.edicoes.show', $edicao);
            $pubDate = $edicao->data_publicacao ? $edicao->data_publicacao->toRfc822String() : $edicao->created_at->toRfc822String();
            
            $description = "Edição {$edicao->numero} com {$edicao->materias->count()} matérias publicadas.";
            if ($edicao->descricao) {
                $description .= " " . strip_tags($edicao->descricao);
            }
            
            $rss .= "
        <item>
            <title><![CDATA[Edição {$edicao->numero} - {$edicao->data->format('d/m/Y')}]]></title>
            <link>{$link}</link>
            <guid isPermaLink=\"true\">{$link}</guid>
            <description><![CDATA[{$description}]]></description>
            <pubDate>{$pubDate}</pubDate>
            <category>Edição</category>
        </item>";
        }
        
        $rss .= $this->generateRSSFooter();
        
        return response($rss)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /**
     * Feed RSS das matérias mais recentes
     */
    public function materias(Request $request)
    {
        $limit = $request->get('limit', 20);
        $materias = Materia::with(['tipo', 'orgao', 'edicoes'])
            ->where('status', 'aprovado')
            ->orderBy('data', 'desc')
            ->limit($limit)
            ->get();

        $rss = $this->generateRSSHeader('Matérias do Diário Oficial', 'Últimas matérias publicadas no Diário Oficial');
        
        foreach ($materias as $materia) {
            $link = route('portal.materias.show', $materia);
            $pubDate = $materia->data->toRfc822String();
            
            $description = strip_tags($materia->texto);
            if (strlen($description) > 300) {
                $description = substr($description, 0, 300) . '...';
            }
            
            $category = $materia->tipo ? $materia->tipo->nome : 'Sem categoria';
            $orgao = $materia->orgao ? $materia->orgao->nome : 'Sem órgão';
            
            $rss .= "
        <item>
            <title><![CDATA[{$materia->titulo}]]></title>
            <link>{$link}</link>
            <guid isPermaLink=\"true\">{$link}</guid>
            <description><![CDATA[{$description}]]></description>
            <pubDate>{$pubDate}</pubDate>
            <category>{$category}</category>
            <author><![CDATA[{$orgao}]]></author>
        </item>";
        }
        
        $rss .= $this->generateRSSFooter();
        
        return response($rss)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /**
     * Feed RSS de matérias por tipo
     */
    public function materiasPortipo(Request $request, $tipoSlug)
    {
        $tipo = Tipo::where('slug', $tipoSlug)
            ->orWhere('id', $tipoSlug)
            ->first();
            
        if (!$tipo) {
            return response()->json(['error' => 'Tipo não encontrado'], 404);
        }

        $limit = $request->get('limit', 20);
        $materias = Materia::with(['orgao', 'edicoes'])
            ->where('tipo_id', $tipo->id)
            ->where('status', 'aprovado')
            ->orderBy('data', 'desc')
            ->limit($limit)
            ->get();

        $rss = $this->generateRSSHeader(
            "Diário Oficial - {$tipo->nome}", 
            "Últimas publicações do tipo {$tipo->nome}"
        );
        
        foreach ($materias as $materia) {
            $link = route('portal.materias.show', $materia);
            $pubDate = $materia->data->toRfc822String();
            
            $description = strip_tags($materia->texto);
            if (strlen($description) > 300) {
                $description = substr($description, 0, 300) . '...';
            }
            
            $orgao = $materia->orgao ? $materia->orgao->nome : 'Sem órgão';
            
            $rss .= "
        <item>
            <title><![CDATA[{$materia->titulo}]]></title>
            <link>{$link}</link>
            <guid isPermaLink=\"true\">{$link}</guid>
            <description><![CDATA[{$description}]]></description>
            <pubDate>{$pubDate}</pubDate>
            <category>{$tipo->nome}</category>
            <author><![CDATA[{$orgao}]]></author>
        </item>";
        }
        
        $rss .= $this->generateRSSFooter();
        
        return response($rss)
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    /**
     * Gera o cabeçalho do RSS
     */
    private function generateRSSHeader($title, $description)
    {
        $url = config('app.url');
        $buildDate = now()->toRfc822String();
        
        return '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title><![CDATA[' . $title . ']]></title>
        <link>' . $url . '</link>
        <description><![CDATA[' . $description . ']]></description>
        <language>pt-BR</language>
        <lastBuildDate>' . $buildDate . '</lastBuildDate>
        <generator>Diário Oficial - Sistema de Publicações</generator>
        <webMaster>admin@diariooficial.local</webMaster>
        <managingEditor>admin@diariooficial.local</managingEditor>
        <atom:link href="' . request()->url() . '" rel="self" type="application/rss+xml" />
        <image>
            <url>' . $url . '/images/logo.png</url>
            <title>' . $title . '</title>
            <link>' . $url . '</link>
        </image>';
    }

    /**
     * Gera o rodapé do RSS
     */
    private function generateRSSFooter()
    {
        return '
    </channel>
</rss>';
    }
}
