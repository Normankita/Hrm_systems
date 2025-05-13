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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('date_of_birth');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('national_id')->unique();
            $table->string('marital_status')->nullable();
            $table->string('residential_address')->nullable();
            $table->string('tin_number')->nullable();
            $table->enum('employee_type', ['Permanent', 'Contract', 'Probation']);
            $table->date('date_of_hire');
            $table->date('date_of_termination')->nullable();
            $table->double('salary')->nullable();
            $table->foreignId('pay_grade_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
