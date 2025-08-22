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
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('workflowable_type'); // Tipo do documento (Materia, Edicao)
            $table->unsignedBigInteger('workflowable_id'); // ID do documento
            $table->integer('step')->default(1); // Etapa atual do workflow
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->unsignedBigInteger('assigned_to')->nullable(); // Usuário responsável pela aprovação atual
            $table->unsignedBigInteger('created_by'); // Quem iniciou o workflow
            $table->text('comments')->nullable(); // Comentários da etapa atual
            $table->json('history')->nullable(); // Histórico de aprovações
            $table->timestamp('due_date')->nullable(); // Prazo para aprovação
            $table->timestamps();
            
            $table->index(['workflowable_type', 'workflowable_id']);
            $table->index(['status']);
            $table->index(['assigned_to']);
            $table->index(['due_date']);
            
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_workflows');
    }
};
