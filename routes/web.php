<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Portal\EdicaoController as PortalEdicaoController;
use App\Http\Controllers\Portal\MateriaController as PortalMateriaController;
use App\Http\Controllers\Admin\EdicaoController;
use App\Http\Controllers\Admin\MateriaController;
use App\Http\Controllers\Admin\TipoController;
use App\Http\Controllers\Admin\OrgaoController;
use App\Http\Controllers\Admin\AssinaturaController;
use App\Http\Controllers\Admin\RelatorioController;
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
});

// Rotas Administrativas
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
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
    
    // Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
