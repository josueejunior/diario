<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Portal\EdicaoController as PortalEdicaoController;
use App\Http\Controllers\Portal\MateriaController as PortalMateriaController;
use App\Http\Controllers\Portal\SubscriptionController;
use App\Http\Controllers\Portal\AtoController;
use App\Http\Controllers\Admin\EdicaoController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\TipoController;
use App\Http\Controllers\Admin\OrgaoController;
use App\Http\Controllers\Admin\AssinaturaController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\AuditController;
// New Controller Imports for Augustinópolis-TO Menu Structure
use App\Http\Controllers\Admin\DiagramacaoController;
use App\Http\Controllers\Admin\MateriasReprovadasController;
use App\Http\Controllers\Admin\InformativoController;
use App\Http\Controllers\Admin\LegislacaoController;
use App\Http\Controllers\Admin\ConfigurarDiarioController;
use App\Http\Controllers\Admin\EntidadeController;
use App\Http\Controllers\Admin\UnidadeController;
use App\Http\Controllers\Admin\TipoArquivoController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\CertificadosNuvemController;
use App\Http\Controllers\Admin\UsuariosRootController;
use App\Http\Controllers\Admin\AjustesGeraisController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\PesquisasSiteController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\UsuariosOnlineController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota da Home Principal
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/buscar', [HomeController::class, 'buscar'])->name('home.buscar');
Route::get('/api/quick-search', [HomeController::class, 'quickSearch'])->name('home.quick-search');

// Rotas Públicas do Portal
Route::prefix('diario')->group(function () {
    // Edições
    Route::get('/edicoes', [PortalEdicaoController::class, 'index'])->name('portal.edicoes.index');
    Route::get('/edicoes/{edicao}', [PortalEdicaoController::class, 'show'])->name('portal.edicoes.show');
    Route::post('/edicoes/{edicao}/view', [PortalEdicaoController::class, 'registerView'])->name('portal.edicoes.view');
    Route::get('/edicoes/{edicao}/materias', [PortalEdicaoController::class, 'materias'])->name('portal.edicoes.materias');
    Route::get('/edicoes/{edicao}/pdf', [PortalEdicaoController::class, 'pdf'])->name('portal.edicoes.pdf');
    
    // Matérias
    Route::get('/materias', [PortalMateriaController::class, 'index'])->name('portal.materias.index');
    Route::get('/materias/{materia}', [PortalMateriaController::class, 'show'])->name('portal.materias.show');
    
    // Documentos por Tipo
    Route::get('/portarias', [PortalMateriaController::class, 'portarias'])->name('portal.documentos.portarias');
    Route::get('/decretos', [PortalMateriaController::class, 'decretos'])->name('portal.documentos.decretos');
    Route::get('/leis', [PortalMateriaController::class, 'leis'])->name('portal.documentos.leis');
    Route::get('/resolucoes', [PortalMateriaController::class, 'resolucoes'])->name('portal.documentos.resolucoes');
    Route::get('/editais', [PortalMateriaController::class, 'editais'])->name('portal.documentos.editais');
    
    // Verificação de Autenticidade
    Route::get('/verificar', [PortalEdicaoController::class, 'verificar'])->name('portal.verificar');
    Route::post('/verificar', [PortalEdicaoController::class, 'verificarHash'])->name('portal.verificar.check');

    // Atos individuais com URLs permanentes
    Route::get('/ato/{materia}', [AtoController::class, 'show'])->name('portal.atos.show');
    Route::get('/ato/{materia}/json', [AtoController::class, 'json'])->name('portal.atos.json');
    Route::get('/atos/sitemap.xml', [AtoController::class, 'sitemap'])->name('portal.atos.sitemap');

    // Notificações e Assinaturas
    Route::prefix('notificacoes')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('portal.subscriptions.index');
        Route::post('/', [SubscriptionController::class, 'store'])->name('portal.subscriptions.store');
        Route::get('/verificar/{token}', [SubscriptionController::class, 'verify'])->name('portal.subscriptions.verify');
        Route::get('/gerenciar/{token}', [SubscriptionController::class, 'manage'])->name('portal.subscriptions.manage');
        Route::put('/gerenciar/{token}', [SubscriptionController::class, 'update'])->name('portal.subscriptions.update');
        Route::delete('/cancelar/{token}', [SubscriptionController::class, 'unsubscribe'])->name('portal.subscriptions.unsubscribe');
        Route::post('/reenviar-verificacao', [SubscriptionController::class, 'resendVerification'])->name('portal.subscriptions.resend');
        Route::post('/solicitar-gerenciamento', [SubscriptionController::class, 'requestManagement'])->name('portal.subscriptions.request');
    });
    
    // Busca Avançada
    Route::get('/busca-avancada', [HomeController::class, 'advancedSearch'])->name('portal.search.advanced');
    Route::post('/busca-rapida', [HomeController::class, 'quickSearch'])->name('portal.search.quick');
});

// Rotas Administrativas
Route::prefix('admin')->middleware(['auth', 'verified', 'audit', 'system_audit'])->group(function () {
    // Dashboard Principal AdminLTE
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Dashboard antigo (manter compatibilidade)
    Route::get('/dashboard-old', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // === DIÁRIO OFICIAL SECTION ===
    // Diagramação
    Route::prefix('diagramacao')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DiagramacaoController::class, 'index'])->name('admin.diagramacao.index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\DiagramacaoController::class, 'show'])->name('admin.diagramacao.show');
        Route::get('/{id}/editar', [\App\Http\Controllers\Admin\DiagramacaoController::class, 'edit'])->name('admin.diagramacao.edit');
        Route::post('/gerar', [\App\Http\Controllers\Admin\DiagramacaoController::class, 'gerar'])->name('admin.diagramacao.gerar');
        Route::post('/salvar', [\App\Http\Controllers\Admin\DiagramacaoController::class, 'salvar'])->name('admin.diagramacao.salvar');
        Route::get('/materias/disponiveis', [\App\Http\Controllers\Admin\DiagramacaoController::class, 'getMateriasDisponiveis'])->name('admin.diagramacao.materias');
        Route::post('/remover-materia', [\App\Http\Controllers\Admin\DiagramacaoController::class, 'removerMateria'])->name('admin.diagramacao.remover-materia');
    });
    
    // Matérias Reprovadas
    Route::prefix('materias-reprovadas')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\MateriasReprovadasController::class, 'index'])->name('admin.materias-reprovadas.index');
        Route::post('/{materia}/revisar', [\App\Http\Controllers\Admin\MateriasReprovadasController::class, 'revisar'])->name('admin.materias-reprovadas.revisar');
        Route::delete('/{materia}', [\App\Http\Controllers\Admin\MateriasReprovadasController::class, 'destroy'])->name('admin.materias-reprovadas.destroy');
    });

    // Informativos
    Route::resource('informativos', \App\Http\Controllers\Admin\InformativoController::class)->names([
        'index' => 'admin.informativos.index',
        'create' => 'admin.informativos.create',
        'store' => 'admin.informativos.store',
        'show' => 'admin.informativos.show',
        'edit' => 'admin.informativos.edit',
        'update' => 'admin.informativos.update',
        'destroy' => 'admin.informativos.destroy'
    ]);

    // Legislação Aplicada
    Route::resource('legislacao', \App\Http\Controllers\Admin\LegislacaoController::class)->names([
        'index' => 'admin.legislacao.index',
        'create' => 'admin.legislacao.create',
        'store' => 'admin.legislacao.store',
        'show' => 'admin.legislacao.show',
        'edit' => 'admin.legislacao.edit',
        'update' => 'admin.legislacao.update',
        'destroy' => 'admin.legislacao.destroy'
    ]);

    // Configurar Diário
    Route::prefix('configurar-diario')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ConfigurarDiarioController::class, 'index'])->name('admin.configurar-diario.index');
        Route::post('/template', [\App\Http\Controllers\Admin\ConfigurarDiarioController::class, 'salvarTemplate'])->name('admin.configurar-diario.template');
        Route::post('/assinatura', [\App\Http\Controllers\Admin\ConfigurarDiarioController::class, 'configurarAssinatura'])->name('admin.configurar-diario.assinatura');
    });

    // === CONFIGURAÇÕES SECTION ===
    // Entidade
    Route::prefix('entidade')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\EntidadeController::class, 'index'])->name('admin.entidade.index');
        Route::post('/', [\App\Http\Controllers\Admin\EntidadeController::class, 'update'])->name('admin.entidade.update');
    });

    // Unidades/Departamentos
    Route::resource('unidades', \App\Http\Controllers\Admin\UnidadeController::class)->names([
        'index' => 'admin.unidades.index',
        'create' => 'admin.unidades.create',
        'store' => 'admin.unidades.store',
        'show' => 'admin.unidades.show',
        'edit' => 'admin.unidades.edit',
        'update' => 'admin.unidades.update',
        'destroy' => 'admin.unidades.destroy'
    ]);

    // Tipos de Arquivos
    Route::resource('tipos-arquivos', \App\Http\Controllers\Admin\TipoArquivoController::class)->names([
        'index' => 'admin.tipos-arquivos.index',
        'create' => 'admin.tipos-arquivos.create',
        'store' => 'admin.tipos-arquivos.store',
        'show' => 'admin.tipos-arquivos.show',
        'edit' => 'admin.tipos-arquivos.edit',
        'update' => 'admin.tipos-arquivos.update',
        'destroy' => 'admin.tipos-arquivos.destroy'
    ]);

    // === FERRAMENTAS SECTION ===
    // Logs do Sistema
    Route::prefix('logs')->group(function () {
        Route::get('/sistema', [\App\Http\Controllers\Admin\LogsController::class, 'sistema'])->name('admin.logs.sistema');
        Route::get('/download', [\App\Http\Controllers\Admin\LogsController::class, 'download'])->name('admin.logs.download');
        Route::post('/clear', [\App\Http\Controllers\Admin\LogsController::class, 'clear'])->name('admin.logs.clear');
        Route::get('/export', [\App\Http\Controllers\Admin\LogsController::class, 'export'])->name('admin.logs.export');
        Route::post('/cleanup', [\App\Http\Controllers\Admin\LogsController::class, 'cleanup'])->name('admin.logs.cleanup');
    });

    // === CERTIFICADOS DIGITAIS SECTION ===
    // Certificados em Nuvem
    Route::prefix('certificados-nuvem')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CertificadosNuvemController::class, 'index'])->name('admin.certificados-nuvem.index');
        Route::post('/upload', [\App\Http\Controllers\Admin\CertificadosNuvemController::class, 'upload'])->name('admin.certificados-nuvem.upload');
        Route::delete('/{certificado}', [\App\Http\Controllers\Admin\CertificadosNuvemController::class, 'destroy'])->name('admin.certificados-nuvem.destroy');
        Route::post('/{certificado}/test', [\App\Http\Controllers\Admin\CertificadosNuvemController::class, 'test'])->name('admin.certificados-nuvem.test');
    });

    // === PLATAFORMA SECTION ===
    // Usuários [Root]
    Route::prefix('usuarios-root')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UsuariosRootController::class, 'index'])->name('admin.usuarios-root.index');
        Route::post('/', [\App\Http\Controllers\Admin\UsuariosRootController::class, 'store'])->name('admin.usuarios-root.store');
        Route::post('/create-new', [\App\Http\Controllers\Admin\UsuariosRootController::class, 'createNew'])->name('admin.usuarios-root.create-new');
        Route::get('/atividade', [\App\Http\Controllers\Admin\UsuariosRootController::class, 'atividade'])->name('admin.usuarios-root.atividade');
        Route::delete('/{usuario}', [\App\Http\Controllers\Admin\UsuariosRootController::class, 'destroy'])->name('admin.usuarios-root.destroy');
    });

    // Ajustes Gerais
    Route::prefix('ajustes-gerais')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AjustesGeraisController::class, 'index'])->name('admin.ajustes-gerais.index');
        Route::post('/', [\App\Http\Controllers\Admin\AjustesGeraisController::class, 'update'])->name('admin.ajustes-gerais.update');
    });

    // Meus Tickets
    Route::prefix('tickets')->group(function () {
        Route::get('/meus', [\App\Http\Controllers\Admin\TicketsController::class, 'meus'])->name('admin.tickets.meus');
        Route::post('/', [\App\Http\Controllers\Admin\TicketsController::class, 'store'])->name('admin.tickets.store');
        Route::get('/{ticket}', [\App\Http\Controllers\Admin\TicketsController::class, 'show'])->name('admin.tickets.show');
        Route::post('/{ticket}/responder', [\App\Http\Controllers\Admin\TicketsController::class, 'responder'])->name('admin.tickets.responder');
    });

    // === SITE SECTION ===
    // Pesquisas do Site
    Route::prefix('pesquisas-site')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PesquisasSiteController::class, 'index'])->name('admin.pesquisas-site.index');
        Route::get('/relatorio', [\App\Http\Controllers\Admin\PesquisasSiteController::class, 'relatorio'])->name('admin.pesquisas-site.relatorio');
        Route::post('/export', [\App\Http\Controllers\Admin\PesquisasSiteController::class, 'export'])->name('admin.pesquisas-site.export');
    });

    // Analytics
    Route::prefix('analytics')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AnalyticsController::class, 'dashboard'])->name('admin.analytics.dashboard');
        Route::get('/visitas', [\App\Http\Controllers\Admin\AnalyticsController::class, 'visitas'])->name('admin.analytics.visitas');
        Route::get('/documentos', [\App\Http\Controllers\Admin\AnalyticsController::class, 'documentos'])->name('admin.analytics.documentos');
    });

    // Usuários Online
    Route::prefix('usuarios-online')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UsuariosOnlineController::class, 'index'])->name('admin.usuarios-online.index');
        Route::get('/sessoes', [\App\Http\Controllers\Admin\UsuariosOnlineController::class, 'sessoes'])->name('admin.usuarios-online.sessoes');
    });    // Edições
    Route::resource('edicoes', EdicaoController::class, ['parameters' => ['edicoes' => 'edicao']])->names([
        'index' => 'admin.edicoes.index',
        'create' => 'admin.edicoes.create',
        'store' => 'admin.edicoes.store',
        'show' => 'admin.edicoes.show',
        'edit' => 'admin.edicoes.edit',
        'update' => 'admin.edicoes.update',
        'destroy' => 'admin.edicoes.destroy'
    ]);
    Route::post('/edicoes/{edicao}/publicar', [EdicaoController::class, 'publicar'])->name('admin.edicoes.publicar');
    Route::post('/edicoes/{edicao}/assinar', [EdicaoController::class, 'assinar'])->name('admin.edicoes.assinar');
    Route::get('/edicoes/{edicao}/pdf', [EdicaoController::class, 'pdf'])->name('admin.edicoes.pdf');
    
    // Matérias
    Route::resource('materias', MateriaController::class, ['parameters' => ['materias' => 'materia']])->names([
        'index' => 'admin.materias.index',
        'create' => 'admin.materias.create',
        'store' => 'admin.materias.store',
        'show' => 'admin.materias.show',
        'edit' => 'admin.materias.edit',
        'update' => 'admin.materias.update',
        'destroy' => 'admin.materias.destroy'
    ]);
    Route::post('/materias/{materia}/aprovar', [MateriaController::class, 'aprovar'])->name('admin.materias.aprovar');
    Route::post('/materias/{materia}/revisar', [MateriaController::class, 'revisar'])->name('admin.materias.revisar');
    
    // Tipos de Matéria
    Route::resource('tipos', TipoController::class, ['parameters' => ['tipos' => 'tipo']])->names([
        'index' => 'admin.tipos.index',
        'create' => 'admin.tipos.create',
        'store' => 'admin.tipos.store',
        'show' => 'admin.tipos.show',
        'edit' => 'admin.tipos.edit',
        'update' => 'admin.tipos.update',
        'destroy' => 'admin.tipos.destroy'
    ]);
    
    // Órgãos
    Route::resource('orgaos', OrgaoController::class, ['parameters' => ['orgaos' => 'orgao']])->names([
        'index' => 'admin.orgaos.index',
        'create' => 'admin.orgaos.create',
        'store' => 'admin.orgaos.store',
        'show' => 'admin.orgaos.show',
        'edit' => 'admin.orgaos.edit',
        'update' => 'admin.orgaos.update',
        'destroy' => 'admin.orgaos.destroy'
    ]);

    // Assinatura Digital
    Route::prefix('assinatura')->group(function () {
        Route::get('/dashboard', [AssinaturaController::class, 'dashboard'])->name('admin.assinatura.dashboard');
        Route::get('/certificados', [AssinaturaController::class, 'certificados'])->name('admin.certificados.index');
        Route::get('/validacao', [AssinaturaController::class, 'validacao'])->name('admin.assinatura.validacao');
        Route::post('/validar', [AssinaturaController::class, 'validar'])->name('admin.assinatura.validar');
    });

    // Notificações & Webhooks
    Route::prefix('notifications')->group(function () {
        Route::get('/subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('admin.subscriptions.index');
        Route::get('/webhooks', [\App\Http\Controllers\Admin\WebhookController::class, 'index'])->name('admin.webhooks.index');
        Route::get('/history', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('admin.notifications.index');
    });

    // Workflow & Aprovação
    Route::prefix('workflow')->group(function () {
        Route::get('/pending', [\App\Http\Controllers\Admin\ApprovalController::class, 'pending'])->name('admin.approval.pending');
        Route::get('/configure', [\App\Http\Controllers\Admin\WorkflowController::class, 'index'])->name('admin.workflow.index');
        Route::get('/history', [\App\Http\Controllers\Admin\ApprovalController::class, 'history'])->name('admin.approval.history');
    });

    // Relatórios
    Route::prefix('relatorios')->group(function () {
        Route::get('/', [RelatorioController::class, 'index'])->name('admin.relatorios.index');
        Route::get('/downloads', [RelatorioController::class, 'downloads'])->name('admin.relatorios.downloads');
        Route::get('/visualizacoes', [RelatorioController::class, 'visualizacoes'])->name('admin.relatorios.visualizacoes');
        Route::get('/publicacoes', [RelatorioController::class, 'publicacoes'])->name('admin.relatorios.publicacoes');
    });

    // Usuários & Permissões
    Route::prefix('users')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('admin.users.index');
        Route::get('/create', [\App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('admin.users.create');
        Route::post('/', [\App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('admin.users.show');
        Route::get('/{user}/edit', [\App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('admin.users.edit');
        Route::put('/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('admin.users.update');
        Route::delete('/{user}', [\App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('admin.users.destroy');
        Route::post('/{user}/toggle-status', [\App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('admin.users.toggle-status');
        Route::post('/{user}/reset-password', [\App\Http\Controllers\Admin\UserManagementController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::post('/{user}/restore', [\App\Http\Controllers\Admin\UserManagementController::class, 'restore'])->name('admin.users.restore');
        
        Route::get('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('admin.permissions.index');
    });

    // Configurações
    Route::prefix('configuracoes')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'index'])->name('admin.configuracoes.index');
        Route::get('/geral', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'geral'])->name('admin.configuracoes.geral');
        Route::post('/geral', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'atualizarGeral'])->name('admin.configuracoes.geral.update');
        
        Route::get('/email', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'email'])->name('admin.configuracoes.email');
        Route::post('/email', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'atualizarEmail'])->name('admin.configuracoes.email.update');
        Route::post('/email/test', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'testarEmail'])->name('admin.configuracoes.email.test');
        
        Route::get('/whatsapp', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'whatsapp'])->name('admin.configuracoes.whatsapp');
        Route::post('/whatsapp', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'atualizarWhatsapp'])->name('admin.configuracoes.whatsapp.update');
        Route::post('/whatsapp/test', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'testarWhatsapp'])->name('admin.configuracoes.whatsapp.test');
    });

    // Sistema & Backup
    Route::prefix('sistema')->group(function () {
        Route::get('/info', [\App\Http\Controllers\Admin\AdminController::class, 'systemInfo'])->name('admin.sistema.info');
        Route::post('/cache/clear', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'limparCache'])->name('admin.sistema.cache.clear');
        Route::post('/optimize', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'otimizar'])->name('admin.sistema.optimize');
    });

    Route::prefix('backup')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'backup'])->name('admin.backup.index');
        Route::post('/create', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'criarBackup'])->name('admin.backup.create');
        Route::get('/download/{filename}', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'downloadBackup'])->name('admin.backup.download');
        Route::delete('/{filename}', [\App\Http\Controllers\Admin\ConfiguracaoController::class, 'excluirBackup'])->name('admin.backup.delete');
    });

    // Ferramentas
    Route::prefix('ferramentas')->group(function () {
        Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'advanced'])->name('admin.search.advanced');
        Route::get('/migration', [\App\Http\Controllers\Admin\MigrationController::class, 'dashboard'])->name('admin.migration.dashboard');
        Route::get('/observability', [\App\Http\Controllers\Admin\ObservabilityController::class, 'index'])->name('admin.ferramentas.observability');
        Route::get('/accessibility', [\App\Http\Controllers\Admin\AccessibilityController::class, 'index'])->name('admin.ferramentas.accessibility');
    });

    // API & Dados Abertos
    Route::prefix('api')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\ApiController::class, 'dashboard'])->name('admin.api.dashboard');
        Route::get('/tokens', [\App\Http\Controllers\Admin\ApiController::class, 'tokens'])->name('admin.api.tokens');
        Route::get('/opendata', [\App\Http\Controllers\Admin\OpenDataController::class, 'catalog'])->name('admin.opendata.catalog');
    });

    // Busca Global
    Route::get('/search', [\App\Http\Controllers\Admin\AdminController::class, 'search'])->name('admin.search');

    // Perfil do Usuário AdminLTE
    Route::get('/perfil', [\App\Http\Controllers\Admin\AdminController::class, 'profileEdit'])->name('admin.profile.edit');
    Route::post('/perfil', [\App\Http\Controllers\Admin\AdminController::class, 'profileUpdate'])->name('admin.profile.update');
    
    // Auditoria
    Route::prefix('auditoria')->middleware('audit')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('admin.audit.index');
        Route::get('/dashboard', [AuditController::class, 'dashboard'])->name('admin.audit.dashboard');
        Route::get('/exportar', [AuditController::class, 'export'])->name('admin.audit.export');
        Route::get('/{auditLog}', [AuditController::class, 'show'])->name('admin.audit.show');
        Route::post('/limpar', [AuditController::class, 'cleanup'])->name('admin.audit.cleanup');
    });

    // Manter rotas antigas para compatibilidade (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
