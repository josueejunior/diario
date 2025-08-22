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
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // login, logout, create, update, delete, view, etc
            $table->string('module'); // users, materias, edicoes, etc
            $table->string('resource_type')->nullable(); // Modelo afetado
            $table->unsignedBigInteger('resource_id')->nullable(); // ID do registro afetado
            $table->string('description'); // Descrição da ação
            $table->json('old_values')->nullable(); // Valores antigos (para updates/deletes)
            $table->json('new_values')->nullable(); // Valores novos (para creates/updates)
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            $table->integer('response_code')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Índices para performance
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['module', 'created_at']);
            $table->index(['resource_type', 'resource_id']);
            $table->index('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
