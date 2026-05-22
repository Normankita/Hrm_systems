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
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->string('type');
            $table->string('status');
            $table->string('duration');
            $table->string('location');
            $table->string('instructor');
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('company_id')->constrained()
                ->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};
