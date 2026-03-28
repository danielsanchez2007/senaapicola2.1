<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_nickname', 255)->nullable();
            $table->string('role_name', 100)->nullable();
            $table->string('action', 100);
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('logged_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
