<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('production_monitorings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('apiary_id')->constrained('apiaries')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('product', 100);
            $table->enum('action', ['entry', 'exit']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_monitorings');
    }
};
