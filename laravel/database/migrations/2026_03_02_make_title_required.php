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
        // Primeiro, garantir que nenhum título seja nulo ou vazio
        DB::table('tasks')
            ->whereNull('title')
            ->orWhere('title', '')
            ->orWhere('title', '(SEM TITULO)')
            ->update(['title' => 'Sem Titulo - Corrigir']);

        // Agora tornar o campo NOT NULL
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title', 255)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('title', 255)->nullable()->change();
        });
    }
};

