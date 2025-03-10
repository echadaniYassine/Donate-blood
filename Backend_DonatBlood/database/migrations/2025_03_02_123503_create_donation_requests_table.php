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
            // Hospital that requested the donation
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            // Donor who has been assigned to the donation request (nullable initially)
            $table->foreignId('donor_id')->nullable()->constrained()->onDelete('cascade');
            // Type of blood needed
            $table->enum('blood_type_needed', ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']);
            // Urgency level of the donation request
            $table->enum('urgency_level', ['low', 'medium', 'high']);
            // Current status of the donation request
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            // Quantity of blood needed
            $table->integer('quantity_needed')->default(1);
            // Timestamp of when the request was posted
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_requests');
    }
}
