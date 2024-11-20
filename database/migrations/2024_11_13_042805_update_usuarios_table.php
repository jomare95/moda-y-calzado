<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Primero intentamos eliminar las columnas si existen
            if (Schema::hasColumn('usuarios', 'created_at')) {
                $table->dropColumn('created_at');
            }
            if (Schema::hasColumn('usuarios', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('usuarios', 'remember_token')) {
                $table->dropColumn('remember_token');
            }

            // Ahora agregamos las columnas correctamente
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->rememberToken();
        });
    }

    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at', 'remember_token']);
        });
    }
};