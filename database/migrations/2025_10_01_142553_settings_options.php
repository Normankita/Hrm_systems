<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This is for those settings that can have range of values,
     * here we will have those other values of that particular setting
     */
    public function up(): void
    {
        Schema::create('settings_options', function(Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->json('values');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
