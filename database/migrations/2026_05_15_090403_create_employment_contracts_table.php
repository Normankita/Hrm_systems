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
     // Migration
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            $table->string('contract_number')->unique();

            $table->foreignId('contract_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();

            // Contract details
            $table->enum('contract_type', ['Fixed', 'Permanent', 'Probation'])->nullable();

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('probation_end_date')->nullable();

            // Job info
            $table->string('work_location')->nullable();

            // Salary & work terms
            $table->decimal('basic_salary', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->string('payment_frequency')->nullable();

            // Status & termination
            $table->string('contract_status')->default('active');
            $table->text('termination_reason')->nullable();

            // Signing
            $table->date('signed_date')->nullable();
            
            // Audit
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_contracts');
    }
};
