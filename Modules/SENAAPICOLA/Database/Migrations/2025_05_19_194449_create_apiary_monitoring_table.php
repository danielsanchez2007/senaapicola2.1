<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('apiary_monitorings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('apiary_id')->constrained('apiaries')->onDelete('cascade');
            $table->string('user_nickname', 100)->nullable();
            $table->string('role', 20);
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_nickname')->references('nickname')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apiary_monitorings');
    }
};
