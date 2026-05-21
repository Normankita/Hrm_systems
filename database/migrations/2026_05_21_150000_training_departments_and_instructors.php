<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_training', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['training_id', 'department_id']);
        });

        Schema::create('training_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->string('name');
            $table->foreignId('employee_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        if (Schema::hasColumn('trainings', 'department_id')) {
            $trainings = DB::table('trainings')->whereNotNull('department_id')->get();
            foreach ($trainings as $training) {
                DB::table('department_training')->insert([
                    'training_id' => $training->id,
                    'department_id' => $training->department_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasColumn('trainings', 'instructor')) {
            $trainings = DB::table('trainings')->whereNotNull('instructor')->where('instructor', '!=', '')->get();
            foreach ($trainings as $training) {
                $names = array_filter(array_map('trim', preg_split('/[,;]+/', $training->instructor)));
                foreach ($names as $name) {
                    DB::table('training_instructors')->insert([
                        'company_id' => $training->company_id,
                        'training_id' => $training->id,
                        'name' => $name,
                        'employee_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        Schema::table('trainings', function (Blueprint $table) {
            if (Schema::hasColumn('trainings', 'department_id')) {
                $table->dropForeign(['department_id']);
                $table->dropColumn('department_id');
            }
            if (Schema::hasColumn('trainings', 'instructor')) {
                $table->dropColumn('instructor');
            }
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('instructor')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::dropIfExists('training_instructors');
        Schema::dropIfExists('department_training');
    }
};
