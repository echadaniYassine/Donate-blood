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
            $table->string('name');  // Hospital name
            $table->string('location');  // Location field (it was missing in your previous migration)
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('hospitals');
    }
}
