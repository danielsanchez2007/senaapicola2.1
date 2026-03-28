<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 20)->default('pending');
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('evidence_path', 255)->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_assignments');
    }
};
