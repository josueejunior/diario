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
    Route::post('/verificar', [PortalEdicaoController::class, 'verificarHash'])->name('portal.verificar');

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
Route::prefix('admin')->middleware(['auth', 'verified', 'audit'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Edições
    Route::resource('edicoes', EdicaoController::class, ['parameters' => ['edicoes' => 'edicao']]);
    Route::post('/edicoes/{edicao}/publicar', [EdicaoController::class, 'publicar'])->name('edicoes.publicar');
    Route::post('/edicoes/{edicao}/assinar', [EdicaoController::class, 'assinar'])->name('edicoes.assinar');
    
    // Matérias
    Route::resource('materias', MateriaController::class, ['parameters' => ['materias' => 'materia']]);
    Route::post('/materias/{materia}/aprovar', [MateriaController::class, 'aprovar'])->name('materias.aprovar');
    Route::post('/materias/{materia}/revisar', [MateriaController::class, 'revisar'])->name('materias.revisar');
    
    // Tipos de Matéria
    Route::resource('tipos', TipoController::class, ['parameters' => ['tipos' => 'tipo']]);
    
    // Órgãos
    Route::resource('orgaos', OrgaoController::class, ['parameters' => ['orgaos' => 'orgao']]);
    
    // Assinaturas
    Route::get('/assinaturas', [AssinaturaController::class, 'index'])->name('assinaturas.index');
    Route::get('/assinaturas/{assinatura}', [AssinaturaController::class, 'show'])->name('assinaturas.show');
    
    // Relatórios
    Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
    Route::get('/relatorios/downloads', [RelatorioController::class, 'downloads'])->name('relatorios.downloads');
    Route::get('/relatorios/visualizacoes', [RelatorioController::class, 'visualizacoes'])->name('relatorios.visualizacoes');
    Route::get('/relatorios/publicacoes', [RelatorioController::class, 'publicacoes'])->name('relatorios.publicacoes');
    
    // Auditoria
    Route::prefix('auditoria')->middleware('audit')->group(function () {
        Route::get('/', [AuditController::class, 'index'])->name('admin.audit.index');
        Route::get('/dashboard', [AuditController::class, 'dashboard'])->name('admin.audit.dashboard');
        Route::get('/exportar', [AuditController::class, 'export'])->name('admin.audit.export');
        Route::get('/{auditLog}', [AuditController::class, 'show'])->name('admin.audit.show');
        Route::post('/limpar', [AuditController::class, 'cleanup'])->name('admin.audit.cleanup');
    });
    
    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
