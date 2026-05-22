<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('Enrolled');
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['training_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_participants');
    }
};
