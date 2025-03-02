<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('contact_number');
            $table->json('stock_management')->default(json_encode([])); // ✅ Default empty array
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('hospitals');
    }
};
