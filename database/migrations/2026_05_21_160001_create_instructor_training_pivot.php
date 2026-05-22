<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructor_training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['instructor_id', 'training_id']);
        });

        if (Schema::hasTable('training_instructors')) {
            $rows = DB::table('training_instructors')->get();
            $instructorMap = [];

            foreach ($rows as $row) {
                $key = $row->company_id . '|' . strtolower(trim($row->name));

                if (! isset($instructorMap[$key])) {
                    $instructorId = DB::table('instructors')->insertGetId([
                        'company_id' => $row->company_id,
                        'name' => trim($row->name),
                        'email' => null,
                        'phone' => null,
                        'specialization' => null,
                        'employee_id' => $row->employee_id,
                        'is_active' => true,
                        'notes' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $instructorMap[$key] = $instructorId;
                }

                $instructorId = $instructorMap[$key];

                $exists = DB::table('instructor_training')
                    ->where('instructor_id', $instructorId)
                    ->where('training_id', $row->training_id)
                    ->exists();

                if (! $exists) {
                    DB::table('instructor_training')->insert([
                        'instructor_id' => $instructorId,
                        'training_id' => $row->training_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            Schema::dropIfExists('training_instructors');
        }
    }

    public function down(): void
    {
        Schema::create('training_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::dropIfExists('instructor_training');
    }
};
