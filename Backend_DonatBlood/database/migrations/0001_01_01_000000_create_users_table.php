<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'hospital', 'donor']);
            $table->timestamps();
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
