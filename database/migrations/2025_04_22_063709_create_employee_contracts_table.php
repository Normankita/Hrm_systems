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
        Schema::create('employee_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('contract_type', ['Fixed', 'Permanent', 'Probation']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('job_title');
            $table->string('work_location');
            $table->foreignId('supervisor_id')->nullable()->constrained('employees');
            $table->string('contract_file')->nullable(); // path to scanned file
            $table->timestamps();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_contracts');
    }
};
