<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('edicoes', function (Blueprint $table) {
            $table->boolean('publicado')->default(false)->after('tamanho');
            $table->dateTime('data_publicacao')->nullable()->after('data');
            $table->text('descricao')->nullable()->after('tamanho');
            
            // Se a coluna já existe, vamos apenas renomear
            if (Schema::hasColumn('edicoes', 'arquivo_pdf')) {
                DB::statement('ALTER TABLE edicoes CHANGE arquivo_pdf caminho_arquivo VARCHAR(255)');
            } else if (!Schema::hasColumn('edicoes', 'caminho_arquivo')) {
                $table->string('caminho_arquivo')->nullable()->after('hash');
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
            
            if (Schema::hasColumn('edicoes', 'caminho_arquivo')) {
                DB::statement('ALTER TABLE edicoes CHANGE caminho_arquivo arquivo_pdf VARCHAR(255)');
            }
        });
    }
};
