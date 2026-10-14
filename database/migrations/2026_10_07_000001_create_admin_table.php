<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel admin.
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();                       // id (PK, auto increment)
            $table->string('username')->unique();
            $table->string('password');         // disimpan dalam bentuk hash (bcrypt)
        });
    }

    /**
     * Menghapus tabel admin.
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
};

