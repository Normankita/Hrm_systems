<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_relation_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('documentable_type');
            $table->unsignedBigInteger('documentable_id');
            $table->index(['documentable_type', 'documentable_id'], 'er_document_morph_idx');
            $table->string('file_path');
            $table->string('original_name');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_relation_documents');
    }
};
