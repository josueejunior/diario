<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RSSController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\EdicaoController;
use App\Http\Controllers\Api\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rotas públicas da API
Route::prefix('v1')->group(function () {
    
    // RSS Feeds
    Route::get('/rss/edicoes', [RSSController::class, 'edicoes'])->name('api.rss.edicoes');
    Route::get('/rss/materias', [RSSController::class, 'materias'])->name('api.rss.materias');
    Route::get('/rss/tipo/{tipo}', [RSSController::class, 'materiasPortipo'])->name('api.rss.tipo');
    
    // API de Documentos
    Route::prefix('documentos')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('api.documentos.index');
        Route::get('/edicoes', [EdicaoController::class, 'index'])->name('api.edicoes.index');
        Route::get('/edicoes/{edicao}', [EdicaoController::class, 'show'])->name('api.edicoes.show');
        Route::get('/materias', [DocumentController::class, 'materias'])->name('api.materias.index');
        Route::get('/materias/{materia}', [DocumentController::class, 'show'])->name('api.materias.show');
        Route::get('/tipos', [DocumentController::class, 'tipos'])->name('api.tipos.index');
        Route::get('/orgaos', [DocumentController::class, 'orgaos'])->name('api.orgaos.index');
    });
    
    // Verificação de Autenticidade via API
    Route::post('/verificar/{hash}', [DocumentController::class, 'verificarHash'])->name('api.verificar');
    
    // Estatísticas Públicas
    Route::get('/estatisticas', [DocumentController::class, 'estatisticas'])->name('api.estatisticas');
    
    // Webhooks e Dados Abertos
    Route::prefix('webhooks')->group(function () {
        Route::get('/', [WebhookController::class, 'index'])->name('api.webhooks.index');
        Route::post('/', [WebhookController::class, 'store'])->name('api.webhooks.store');
        Route::delete('/{id}', [WebhookController::class, 'destroy'])->name('api.webhooks.destroy');
        Route::post('/{id}/test', [WebhookController::class, 'test'])->name('api.webhooks.test');
    });
    
    // Portal CKAN-style para dados abertos
    Route::get('/catalog', [WebhookController::class, 'catalog'])->name('api.catalog');
});

// Rotas autenticadas da API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // APIs administrativas (requerem autenticação)
    Route::prefix('admin')->group(function () {
        Route::post('/edicoes/{edicao}/publicar', [EdicaoController::class, 'publicar'])->name('api.admin.edicoes.publicar');
        Route::post('/edicoes/{edicao}/assinar', [EdicaoController::class, 'assinar'])->name('api.admin.edicoes.assinar');
    });
});
