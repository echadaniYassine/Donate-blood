<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('blood_type');
            $table->date('last_donation_date')->nullable();
            $table->boolean('availability_status')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('donors');
    }
};
