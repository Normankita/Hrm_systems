<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_pay_grade', function (Blueprint $table) {
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('pay_grade_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('assigned_by')->nullable();
            $table->date('effective_from');
            $table->decimal('base_salary_override',8,2)->default(0)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_pay_grade');
    }
};
