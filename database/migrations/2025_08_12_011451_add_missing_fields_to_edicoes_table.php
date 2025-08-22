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
            if (!Schema::hasColumn('edicoes', 'publicado')) {
                $table->boolean('publicado')->default(false)->after('tamanho');
            }
            if (!Schema::hasColumn('edicoes', 'data_publicacao')) {
                $table->dateTime('data_publicacao')->nullable()->after('data');
            }
            if (!Schema::hasColumn('edicoes', 'descricao')) {
                $table->text('descricao')->nullable()->after('tamanho');
            }
        });
        
        // Handle arquivo_pdf -> caminho_arquivo renaming separately
        if (Schema::hasColumn('edicoes', 'arquivo_pdf') && !Schema::hasColumn('edicoes', 'caminho_arquivo')) {
            DB::statement('ALTER TABLE edicoes CHANGE arquivo_pdf caminho_arquivo VARCHAR(255)');
        } else if (!Schema::hasColumn('edicoes', 'arquivo_pdf') && !Schema::hasColumn('edicoes', 'caminho_arquivo')) {
            Schema::table('edicoes', function (Blueprint $table) {
                $table->string('caminho_arquivo')->nullable()->after('hash');
            });
        }
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
