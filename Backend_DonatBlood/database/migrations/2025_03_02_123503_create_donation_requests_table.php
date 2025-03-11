<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('donation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->foreignId('donor_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('blood_type_needed', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']);
            $table->enum('urgency_level', ['low', 'medium', 'high']);
            $table->enum('status', ['pending', 'fulfilled'])->default('pending'); // Ensure status column exists
            $table->integer('quantity_needed')->default(1);
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_requests');
    }
}
