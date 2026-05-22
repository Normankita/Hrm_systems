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
        Schema::create('contributions_divisions', function (Blueprint $table) {
            $table->id();
            // start by company contribution divisio
            $table->decimal('company_nssf', 20, 2);
            $table->decimal('company_psssf', 20, 2);
            $table->decimal('company_paye', 20, 2);
            $table->decimal('company_sdl', 20, 2);
            $table->decimal('company_wcf', 20, 2);
            // employee contribution division
            $table->decimal('employee_nssf', 20, 2);
            $table->decimal('employee_psssf', 20, 2);
            $table->decimal('employee_paye', 20, 2);
            $table->decimal('employee_sdl', 20, 2); 
            $table->decimal('employee_wcf', 20, 2);

            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            // indexing the company id column
            $table->index('company_id');

            $table->foreignId('payroll_id')->constrained()->onDelete('cascade');   
            $table->index('payroll_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions_divisions');
    }
};
