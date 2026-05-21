<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('reference_number')->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('subject');
            $table->text('description');
            $table->date('complaint_date');
            $table->string('severity')->default('Medium');
            $table->string('status')->default('Open');
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('employee_disciplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('reference_number')->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('action_type');
            $table->text('description');
            $table->date('discipline_date');
            $table->string('status')->default('Open');
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('employee_conflicts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('reference_number')->unique();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('other_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('subject');
            $table->text('description');
            $table->date('conflict_date');
            $table->string('severity')->default('Medium');
            $table->string('status')->default('Open');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('employee_relation_resolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('reference_number')->unique();
            $table->string('resolvable_type');
            $table->unsignedBigInteger('resolvable_id');
            $table->index(['resolvable_type', 'resolvable_id'], 'er_resolution_morph_idx');
            $table->string('title');
            $table->text('summary');
            $table->text('action_taken')->nullable();
            $table->string('status')->default('Open');
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_relation_resolutions');
        Schema::dropIfExists('employee_conflicts');
        Schema::dropIfExists('employee_disciplines');
        Schema::dropIfExists('employee_complaints');
    }
};
