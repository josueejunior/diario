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
            $table->text('descricao')->nullable()->after('tipo');
            $table->boolean('publicado')->default(false)->after('descricao');
            $table->string('caminho_arquivo')->nullable()->after('arquivo_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('edicoes', function (Blueprint $table) {
            $table->dropColumn(['descricao', 'publicado', 'caminho_arquivo']);
        });
    }
};
