<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AccessibilityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Apenas processar respostas HTML
        if ($response->headers->get('Content-Type') && 
            strpos($response->headers->get('Content-Type'), 'text/html') !== false) {
            
            $content = $response->getContent();
            $content = $this->enhanceAccessibility($content);
            $response->setContent($content);
        }

        return $response;
    }

    /**
     * Melhora a acessibilidade do HTML
     */
    private function enhanceAccessibility($html): string
    {
        // 1. Adicionar skip link se não existir
        if (strpos($html, 'skip-to-main') === false) {
            $skipLink = '<a href="#main-content" class="skip-link visually-hidden-focusable">Pular para o conteúdo principal</a>';
            $html = str_replace('<body', $skipLink . '<body', $html);
        }

        // 2. Garantir que main content tem ID
        if (strpos($html, 'id="main-content"') === false) {
            $html = preg_replace('/<main([^>]*)>/', '<main$1 id="main-content">', $html);
        }

        // 3. Adicionar role="main" se não existir
        if (strpos($html, 'role="main"') === false && strpos($html, '<main') !== false) {
            $html = preg_replace('/<main([^>]*)>/', '<main$1 role="main">', $html);
        }

        // 4. Melhorar contraste - adicionar classe para alto contraste
        $html = $this->addContrastControls($html);

        // 5. Garantir que imagens têm alt text
        $html = $this->ensureImageAltText($html);

        // 6. Adicionar aria-labels em links genéricos
        $html = $this->improveLinks($html);

        // 7. Adicionar scripts de acessibilidade
        $html = $this->addAccessibilityScripts($html);

        return $html;
    }

    /**
     * Adiciona controles de contraste
     */
    private function addContrastControls($html): string
    {
        $contrastControls = '
        <div id="accessibility-controls" class="accessibility-controls" role="toolbar" aria-label="Controles de Acessibilidade">
            <button id="toggle-contrast" class="btn-accessibility" aria-label="Alternar Alto Contraste" title="Alto Contraste">
                <i class="fas fa-adjust" aria-hidden="true"></i>
                <span class="sr-only">Alto Contraste</span>
            </button>
            <button id="increase-font" class="btn-accessibility" aria-label="Aumentar Fonte" title="Aumentar Fonte">
                <i class="fas fa-plus" aria-hidden="true"></i>
                <span class="sr-only">Aumentar Fonte</span>
            </button>
            <button id="decrease-font" class="btn-accessibility" aria-label="Diminuir Fonte" title="Diminuir Fonte">
                <i class="fas fa-minus" aria-hidden="true"></i>
                <span class="sr-only">Diminuir Fonte</span>
            </button>
            <button id="reset-accessibility" class="btn-accessibility" aria-label="Resetar Configurações" title="Resetar">
                <i class="fas fa-undo" aria-hidden="true"></i>
                <span class="sr-only">Resetar</span>
            </button>
        </div>';

        // Inserir após abertura do body
        $html = str_replace('<body', $contrastControls . '<body', $html);

        return $html;
    }

    /**
     * Garante que imagens têm texto alternativo
     */
    private function ensureImageAltText($html): string
    {
        // Procurar imagens sem alt ou com alt vazio
        $html = preg_replace_callback('/<img([^>]*?)>/', function($matches) {
            $imgTag = $matches[0];
            
            // Se já tem alt, não modificar
            if (strpos($imgTag, 'alt=') !== false) {
                return $imgTag;
            }
            
            // Adicionar alt vazio para imagens decorativas
            if (strpos($imgTag, 'decorative') !== false || 
                strpos($imgTag, 'decoration') !== false) {
                return str_replace('>', ' alt="" role="presentation">', $imgTag);
            }
            
            // Tentar extrair significado do src ou class
            $altText = $this->generateAltText($imgTag);
            return str_replace('>', ' alt="' . $altText . '">', $imgTag);
            
        }, $html);

        return $html;
    }

    /**
     * Gera texto alternativo baseado no contexto
     */
    private function generateAltText($imgTag): string
    {
        // Extrair src
        if (preg_match('/src=["\']([^"\']*)["\']/', $imgTag, $matches)) {
            $src = $matches[1];
            $filename = basename($src, '.' . pathinfo($src, PATHINFO_EXTENSION));
            
            // Limpar e humanizar o nome do arquivo
            $altText = str_replace(['-', '_'], ' ', $filename);
            $altText = ucwords($altText);
            
            return $altText;
        }
        
        return 'Imagem';
    }

    /**
     * Melhora links genéricos
     */
    private function improveLinks($html): string
    {
        // Links com texto genérico
        $genericLinkPatterns = [
            'clique aqui' => 'link',
            'saiba mais' => 'mais informações',
            'leia mais' => 'continuar lendo',
            'ver mais' => 'ver detalhes',
            'aqui' => 'link'
        ];

        foreach ($genericLinkPatterns as $pattern => $improvement) {
            $html = preg_replace_callback(
                '/<a([^>]*?)>' . preg_quote($pattern, '/') . '<\/a>/i',
                function($matches) use ($improvement) {
                    $linkAttrs = $matches[1];
                    $linkText = $matches[0];
                    
                    // Adicionar aria-label se não existir
                    if (strpos($linkAttrs, 'aria-label') === false) {
                        $linkAttrs .= ' aria-label="' . $improvement . '"';
                    }
                    
                    return '<a' . $linkAttrs . '>' . $matches[0] . '</a>';
                },
                $html
            );
        }

        return $html;
    }

    /**
     * Adiciona scripts de acessibilidade
     */
    private function addAccessibilityScripts($html): string
    {
        $accessibilityScript = '
        <script>
        // Acessibilidade - Alto Contraste e Fonte
        (function() {
            const body = document.body;
            
            // Carregar preferências salvas
            if (localStorage.getItem("high-contrast") === "true") {
                body.classList.add("high-contrast");
            }
            
            const fontSize = localStorage.getItem("font-size");
            if (fontSize) {
                body.style.fontSize = fontSize;
            }
            
            // Toggle Alto Contraste
            document.addEventListener("click", function(e) {
                if (e.target.closest("#toggle-contrast")) {
                    body.classList.toggle("high-contrast");
                    localStorage.setItem("high-contrast", body.classList.contains("high-contrast"));
                }
                
                // Aumentar fonte
                if (e.target.closest("#increase-font")) {
                    const currentSize = parseFloat(getComputedStyle(body).fontSize);
                    const newSize = Math.min(currentSize * 1.1, 24);
                    body.style.fontSize = newSize + "px";
                    localStorage.setItem("font-size", newSize + "px");
                }
                
                // Diminuir fonte
                if (e.target.closest("#decrease-font")) {
                    const currentSize = parseFloat(getComputedStyle(body).fontSize);
                    const newSize = Math.max(currentSize * 0.9, 12);
                    body.style.fontSize = newSize + "px";
                    localStorage.setItem("font-size", newSize + "px");
                }
                
                // Reset
                if (e.target.closest("#reset-accessibility")) {
                    body.classList.remove("high-contrast");
                    body.style.fontSize = "";
                    localStorage.removeItem("high-contrast");
                    localStorage.removeItem("font-size");
                }
            });
            
            // Navegação por teclado melhorada
            document.addEventListener("keydown", function(e) {
                // ESC para fechar modais
                if (e.key === "Escape") {
                    const modal = document.querySelector(".modal.show");
                    if (modal) {
                        const closeBtn = modal.querySelector("[data-bs-dismiss=modal]");
                        if (closeBtn) closeBtn.click();
                    }
                }
                
                // Enter e Space para elementos clicáveis
                if (e.key === "Enter" || e.key === " ") {
                    const target = e.target;
                    if (target.getAttribute("role") === "button" || 
                        target.classList.contains("btn-accessibility")) {
                        e.preventDefault();
                        target.click();
                    }
                }
            });
            
            // Indicador de foco visível
            document.addEventListener("keydown", function(e) {
                if (e.key === "Tab") {
                    body.classList.add("keyboard-navigation");
                }
            });
            
            document.addEventListener("mousedown", function() {
                body.classList.remove("keyboard-navigation");
            });
            
        })();
        </script>
        
        <style>
        /* Estilos de Acessibilidade */
        .accessibility-controls {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            background: rgba(0,0,0,0.8);
            padding: 10px;
            border-radius: 5px;
            display: flex;
            gap: 5px;
        }
        
        .btn-accessibility {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .btn-accessibility:hover,
        .btn-accessibility:focus {
            background: #007bff;
            color: white;
            outline: 2px solid #fff;
        }
        
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #000;
            color: white;
            padding: 8px;
            text-decoration: none;
            border-radius: 0 0 5px 5px;
            z-index: 10000;
        }
        
        .skip-link:focus {
            top: 0;
        }
        
        .visually-hidden-focusable:not(:focus) {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }
        
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Alto Contraste */
        .high-contrast {
            background: #000 !important;
            color: #fff !important;
        }
        
        .high-contrast * {
            background-color: #000 !important;
            color: #fff !important;
            border-color: #fff !important;
        }
        
        .high-contrast a {
            color: #ffff00 !important;
        }
        
        .high-contrast .btn-primary {
            background-color: #fff !important;
            color: #000 !important;
        }
        
        .high-contrast .card {
            border: 2px solid #fff !important;
        }
        
        /* Foco visível para navegação por teclado */
        .keyboard-navigation *:focus {
            outline: 3px solid #ffbf47 !important;
            outline-offset: 2px !important;
        }
        
        /* Melhorar contraste de texto */
        .text-muted {
            color: #6c757d !important;
        }
        
        .high-contrast .text-muted {
            color: #ccc !important;
        }
        
        /* Garantir tamanho mínimo de touch targets */
        button, .btn, a {
            min-height: 44px;
            min-width: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        </style>';

        // Inserir antes do fechamento do body
        $html = str_replace('</body>', $accessibilityScript . '</body>', $html);

        return $html;
    }
}
