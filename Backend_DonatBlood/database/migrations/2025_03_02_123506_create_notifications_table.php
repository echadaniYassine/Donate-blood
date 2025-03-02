<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->timestamp('read_at')->nullable(); // ✅ Add this line
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('notifications');
    }
};
