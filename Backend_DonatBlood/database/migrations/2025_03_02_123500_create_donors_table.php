<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorsTable extends Migration
{
    public function up()
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cin')->unique();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']);
            $table->string('email')->nullable();  // Copy email from the User model
            $table->string('phone');  // Copy phone from the User model
            $table->date('last_donation_date')->nullable();
            $table->boolean('availability')->default(true);  // Add the 'availability' column with a default value
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donors');
    }
}
