<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->date('donation_date');
            $table->integer('volume_donated'); // حجم الدم بالملليلتر
            $table->enum('status', ['completed', 'canceled'])->default('completed');
            $table->string('blood_bag_serial_number')->unique(); // Add the blood_bag_serial_number column
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('donation_histories');
    }
};