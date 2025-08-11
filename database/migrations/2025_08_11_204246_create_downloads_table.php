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
        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('edicao_id')->constrained('edicoes')->onDelete('cascade');
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('origem')->default('web'); // web, api, etc
            $table->timestamps();
            
            // Índice para contagens e análises
            $table->index(['edicao_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloads');
    }
};
