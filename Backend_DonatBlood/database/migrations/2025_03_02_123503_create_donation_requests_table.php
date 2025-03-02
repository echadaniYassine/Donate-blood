<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_id')->constrained()->onDelete('cascade');
            $table->foreignId('donor_id')->nullable()->constrained()->onDelete('cascade'); // ✅ Nullable
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'completed', 'canceled'])->default('pending');
            $table->date('request_date')->useCurrent(); // ✅ Ensures correct default timestamp
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('donation_requests');
    }
};
