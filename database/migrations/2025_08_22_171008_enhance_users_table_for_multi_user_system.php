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
        Schema::table('users', function (Blueprint $table) {
            // Campos para controle de acesso e perfil
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'editor', 'visualizador', 'super_admin'])->default('visualizador')->after('email');
            }
            
            if (!Schema::hasColumn('users', 'permissions')) {
                $table->json('permissions')->nullable()->after('role');
            }
            
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['ativo', 'inativo', 'suspenso'])->default('ativo')->after('permissions');
            }
            
            if (!Schema::hasColumn('users', 'ultimo_acesso')) {
                $table->timestamp('ultimo_acesso')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('users', 'ip_ultimo_acesso')) {
                $table->string('ip_ultimo_acesso')->nullable()->after('ultimo_acesso');
            }
            
            if (!Schema::hasColumn('users', 'tentativas_login')) {
                $table->integer('tentativas_login')->default(0)->after('ip_ultimo_acesso');
            }
            
            if (!Schema::hasColumn('users', 'bloqueado_ate')) {
                $table->timestamp('bloqueado_ate')->nullable()->after('tentativas_login');
            }
            
            // Campos de perfil expandido
            if (!Schema::hasColumn('users', 'telefone')) {
                $table->string('telefone', 20)->nullable()->after('bloqueado_ate');
            }
            
            if (!Schema::hasColumn('users', 'departamento')) {
                $table->string('departamento')->nullable()->after('telefone');
            }
            
            if (!Schema::hasColumn('users', 'foto_perfil')) {
                $table->string('foto_perfil')->nullable()->after('departamento');
            }
            
            if (!Schema::hasColumn('users', 'created_by')) {
                $table->foreignId('created_by')->nullable()->constrained('users')->after('foto_perfil');
            }
            
            if (!Schema::hasColumn('users', 'observacoes')) {
                $table->text('observacoes')->nullable()->after('created_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [
                'role', 'permissions', 'status', 'ultimo_acesso', 'ip_ultimo_acesso',
                'tentativas_login', 'bloqueado_ate', 'telefone', 'departamento',
                'foto_perfil', 'created_by', 'observacoes'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
