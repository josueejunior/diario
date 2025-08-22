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
        Schema::table('edicoes', function (Blueprint $table) {
            // Renomear a coluna arquivo_pdf para caminho_arquivo se necessário
            if (Schema::hasColumn('edicoes', 'arquivo_pdf') && !Schema::hasColumn('edicoes', 'caminho_arquivo')) {
                $table->renameColumn('arquivo_pdf', 'caminho_arquivo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edicoes', function (Blueprint $table) {
            $table->dropColumn(['publicado', 'data_publicacao', 'descricao']);
            
            if (Schema::hasColumn('edicoes', 'caminho_arquivo') && !Schema::hasColumn('edicoes', 'arquivo_pdf')) {
                $table->renameColumn('caminho_arquivo', 'arquivo_pdf');
            }
        });
    }
};
