<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->string('loan_type');
            $table->decimal('total_payable', 12, 2);
            $table->decimal('monthly_deduction', 12, 2)->nullable();
            $table->integer('months_to_pay');
            $table->date('issued_date');
            $table->enum('status', ['active', 'paid'])->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
