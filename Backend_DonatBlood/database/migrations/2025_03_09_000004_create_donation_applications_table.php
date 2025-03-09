<?php

// database/migrations/2025_03_09_000004_create_donation_applications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('donation_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->foreignId('donation_request_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['applied', 'accepted', 'rejected', 'completed'])->default('applied');
            $table->dateTime('appointment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_applications');
    }
};
