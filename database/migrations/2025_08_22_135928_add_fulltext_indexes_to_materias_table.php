<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            // Adicionar índices FULLTEXT para busca inteligente
            DB::statement('ALTER TABLE materias ADD FULLTEXT(titulo, texto) WITH PARSER ngram');
            
            // Adicionar campos de metadados para busca
            $table->json('keywords')->nullable()->after('texto');
            $table->json('tags')->nullable()->after('keywords');
            $table->string('processo_numero')->nullable()->after('tags');
            $table->index(['tipo_id', 'orgao_id', 'data']);
            $table->index('processo_numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materias', function (Blueprint $table) {
            $table->dropIndex(['tipo_id', 'orgao_id', 'data']);
            $table->dropIndex(['processo_numero']);
            $table->dropColumn(['keywords', 'tags', 'processo_numero']);
        });
        
        // Remover índice FULLTEXT
        DB::statement('ALTER TABLE materias DROP INDEX titulo');
    }
};
