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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('payroll_date');
            $table->double('basic_salary')->nullable();
            $table->double('allowances')->nullable();
            $table->double('deductions')->nullable();
            $table->double('net_salary')->nullable();
            $table->double('paye');
            $table->double('nssf')->nullable();
            $table->double('psssf')->nullable();
            $table->double('sdl')->nullable();
            $table->double('wcf')->nullable();
            $table->double('gross_salary')->nullable();
            $table->string('payslip_path')->nullable();
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
