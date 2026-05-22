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
        Schema::create('designation_role_mappings', function (Blueprint $table) {
            $table->id();

            // Foreign key to designations
            $table->foreignId('designation_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Foreign key to roles
            $table->foreignId('rank_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes(); // for soft deletion
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('designation_role_mappings');
    }
};
