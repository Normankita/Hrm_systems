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
        Schema::create('allowance_frequencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('base_category', ['year','month', 'week']);
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->integer('no_base_times');
            $table->integer('no_times');
            $table->double('days_apart');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowance_frequencies');
    }
};
