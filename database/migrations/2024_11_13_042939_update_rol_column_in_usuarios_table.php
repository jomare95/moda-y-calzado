<<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Primero modificamos la columna rol para aceptar el valor 'admin'
            DB::statement("ALTER TABLE usuarios MODIFY rol ENUM('admin', 'vendedor', 'cajero') NOT NULL");
        });
    }

    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Si necesitas revertir, puedes volver a la definición anterior
            DB::statement("ALTER TABLE usuarios MODIFY rol ENUM('vendedor', 'cajero') NOT NULL");
        });
    }
};