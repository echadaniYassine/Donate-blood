<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('donation_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->foreignId('donation_request_id')->constrained()->onDelete('cascade');
            $table->string('blood_type');
            $table->integer('quantity_donated');  // Amount of blood donated
            $table->timestamp('donation_date');  // Date of donation
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('donation_histories');
    }
};