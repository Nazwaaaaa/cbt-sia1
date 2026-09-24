<?php

use App\Models\Exam;
use App\Models\Subject;
use App\Models\User;
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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Exam::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Subject::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->dateTime('exam_date');

            $table->decimal('score', 5, 2)->default(0);

            $table->unsignedInteger('correct_answers')->default(0);

            $table->unsignedInteger('wrong_answers')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};