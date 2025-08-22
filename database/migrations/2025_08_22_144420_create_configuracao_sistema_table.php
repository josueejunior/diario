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
        Schema::create('configuracao_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->text('valor')->nullable();
            $table->string('descricao')->nullable();
            $table->enum('tipo', ['string', 'integer', 'boolean', 'json', 'text'])->default('string');
            $table->string('grupo')->nullable();
            $table->timestamps();
            
            $table->index(['grupo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracao_sistema');
    }
};
