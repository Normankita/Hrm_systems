<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->foreignId('pay_grade_id')->nullable()->constrained()->onDelete('set null');

            $table->date('payroll_date');
            $table->string('period'); // Format: 'YYYY-MM'

            $table->double('basic_salary')->nullable();
            $table->double('gross_salary')->nullable();
            $table->double('net_salary')->nullable();
            $table->double('paye')->nullable();
            $table->double('nssf')->nullable();
            $table->double('psssf')->nullable();
            $table->double('sdl')->nullable();
            $table->double('wcf')->nullable();
            $table->double('allowances')->nullable();
            $table->double('deductions')->nullable();
            $table->string('payslip_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
             $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->unique(['employee_id', 'period']); // prevent duplicate payrolls per employee per month
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
