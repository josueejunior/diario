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
        Schema::create('pesquisas_site', function (Blueprint $table) {
            $table->id();
            $table->string('termo_pesquisa');
            $table->integer('resultados_encontrados')->default(0);
            $table->string('ip_usuario')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->json('filtros_aplicados')->nullable();
            $table->timestamp('data_pesquisa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesquisas_site');
    }
};
