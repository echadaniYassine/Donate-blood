<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipient_id')->nullable()->constrained()->onDelete('cascade'); // ✅ Nullable for blood bank donations
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->date('donation_date');
            $table->string('blood_bag_serial_number')->unique();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('donation_histories');
    }
};
