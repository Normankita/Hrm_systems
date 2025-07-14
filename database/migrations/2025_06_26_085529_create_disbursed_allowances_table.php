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
        Schema::create('disbursed_allowances', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['group','individual', 'category','custom']);
            $table->double('amount');
            $table->foreignId('company_id');
            $table->foreignId('employee_id')->constrained();
            $table->morphs('disbursable');
            $table->boolean('status')->default(false);
            $table->foreignId('allowance_id')->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursed_allowances');
    }
};
