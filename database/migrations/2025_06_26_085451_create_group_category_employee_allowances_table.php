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
        Schema::create('group_category_employee_allowances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allowance_group_employee_pivot_id');
            $table->foreignId('allowance_id');
            $table->foreignId('allowance_frequency_id');
            $table->double('amount');
            $table->date('effective_from');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'group_category_employee_allowances_pivot');
    }
};
