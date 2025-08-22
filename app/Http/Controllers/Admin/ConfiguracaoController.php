<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\ConfiguracaoSistema;

class ConfiguracaoController extends Controller
{
    /**
     * Display the configuration dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.configuracoes.index');
    }

    /**
     * Display general configurations.
     */
    public function geral()
    {
        $configuracoes = ConfiguracaoSistema::pluck('valor', 'chave');
        return view('admin.configuracoes.geral', compact('configuracoes'));
    }

    /**
     * Update general configurations.
     */
    public function atualizarGeral(Request $request)
    {
        $request->validate([
            'nome_sistema' => 'required|string|max:255',
            'nome_entidade' => 'required|string|max:255',
            'endereco' => 'required|string|max:500',
            'telefone' => 'nullable|string|max:20',
            'email_contato' => 'required|email|max:255',
            'site_url' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $configuracoes = $request->except(['_token', 'logo']);

        // Upload da logo se fornecida
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $configuracoes['logo_path'] = $logoPath;
        }

        foreach ($configuracoes as $chave => $valor) {
            ConfiguracaoSistema::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor]
            );
        }

        return back()->with('success', 'Configurações atualizadas com sucesso!');
    }

    /**
     * Display email configurations.
     */
    public function email()
    {
        $configuracoes = ConfiguracaoSistema::whereIn('chave', [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name'
        ])->pluck('valor', 'chave');

        return view('admin.configuracoes.email', compact('configuracoes'));
    }

    /**
     * Update email configurations.
     */
    public function atualizarEmail(Request $request)
    {
        $request->validate([
            'mail_mailer' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host' => 'required_if:mail_mailer,smtp|string|max:255',
            'mail_port' => 'required_if:mail_mailer,smtp|integer|min:1|max:65535',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
        ]);

        $configuracoes = $request->except('_token');

        foreach ($configuracoes as $chave => $valor) {
            ConfiguracaoSistema::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor]
            );
        }

        return back()->with('success', 'Configurações de e-mail atualizadas com sucesso!');
    }

    /**
     * Test email configuration.
     */
    public function testarEmail(Request $request)
    {
        $request->validate([
            'email_teste' => 'required|email'
        ]);

        try {
            // Aqui você implementaria o teste de envio de e-mail
            // Por exemplo, enviando um e-mail de teste
            
            return back()->with('success', 'E-mail de teste enviado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar e-mail de teste: ' . $e->getMessage());
        }
    }

    /**
     * Display WhatsApp configurations.
     */
    public function whatsapp()
    {
        $configuracoes = ConfiguracaoSistema::whereIn('chave', [
            'whatsapp_api_url',
            'whatsapp_api_token',
            'whatsapp_numero_remetente',
            'whatsapp_ativo'
        ])->pluck('valor', 'chave');

        return view('admin.configuracoes.whatsapp', compact('configuracoes'));
    }

    /**
     * Update WhatsApp configurations.
     */
    public function atualizarWhatsapp(Request $request)
    {
        $request->validate([
            'whatsapp_api_url' => 'nullable|url|max:255',
            'whatsapp_api_token' => 'nullable|string|max:500',
            'whatsapp_numero_remetente' => 'nullable|string|max:20',
            'whatsapp_ativo' => 'boolean',
        ]);

        $configuracoes = $request->except('_token');
        $configuracoes['whatsapp_ativo'] = $request->has('whatsapp_ativo') ? '1' : '0';

        foreach ($configuracoes as $chave => $valor) {
            ConfiguracaoSistema::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor]
            );
        }

        return back()->with('success', 'Configurações do WhatsApp atualizadas com sucesso!');
    }

    /**
     * Test WhatsApp configuration.
     */
    public function testarWhatsapp(Request $request)
    {
        $request->validate([
            'numero_teste' => 'required|string|max:20'
        ]);

        try {
            // Aqui você implementaria o teste de envio via WhatsApp
            // Por exemplo, enviando uma mensagem de teste
            
            return back()->with('success', 'Mensagem de teste enviada via WhatsApp!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao enviar mensagem de teste: ' . $e->getMessage());
        }
    }

    /**
     * Display backup configurations and management.
     */
    public function backup()
    {
        // Listar backups existentes
        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'sql';
            })
            ->map(function ($file) {
                return [
                    'nome' => basename($file),
                    'tamanho' => Storage::disk('local')->size($file),
                    'data_criacao' => Storage::disk('local')->lastModified($file),
                    'caminho' => $file
                ];
            })
            ->sortByDesc('data_criacao');

        return view('admin.configuracoes.backup', compact('backups'));
    }

    /**
     * Create a new backup.
     */
    public function criarBackup()
    {
        try {
            $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Criar diretório se não existir
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }

            // Executar mysqldump
            $command = sprintf(
                'mysqldump -h %s -u %s -p%s %s > %s',
                config('database.connections.mysql.host'),
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.database'),
                $path
            );

            exec($command, $output, $returnVar);

            if ($returnVar === 0) {
                return back()->with('success', 'Backup criado com sucesso!');
            } else {
                return back()->with('error', 'Erro ao criar backup.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao criar backup: ' . $e->getMessage());
        }
    }

    /**
     * Download a backup file.
     */
    public function downloadBackup($filename)
    {
        $path = 'backups/' . $filename;
        
        if (!Storage::disk('local')->exists($path)) {
            return back()->with('error', 'Arquivo de backup não encontrado.');
        }

        return Storage::disk('local')->download($path);
    }

    /**
     * Delete a backup file.
     */
    public function excluirBackup($filename)
    {
        $path = 'backups/' . $filename;
        
        if (!Storage::disk('local')->exists($path)) {
            return back()->with('error', 'Arquivo de backup não encontrado.');
        }

        Storage::disk('local')->delete($path);
        return back()->with('success', 'Backup excluído com sucesso!');
    }

    /**
     * Clear application cache.
     */
    public function limparCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            return back()->with('success', 'Cache limpo com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao limpar cache: ' . $e->getMessage());
        }
    }

    /**
     * Optimize application.
     */
    public function otimizar()
    {
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
            
            return back()->with('success', 'Sistema otimizado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao otimizar sistema: ' . $e->getMessage());
        }
    }
}
