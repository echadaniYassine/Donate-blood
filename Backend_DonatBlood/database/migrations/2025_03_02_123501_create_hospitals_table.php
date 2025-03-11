<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalsTable extends Migration
{
    public function up()
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');  
            $table->string('email')->nullable();  // Copy email from the User model
            $table->string('phone');  // Copy phone from the User model
            $table->string('location');  // Location field (it was missing in your previous migration)
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('hospitals');
    }
}
