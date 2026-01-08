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
        Schema::table('imaster.case_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('imaster.cases')->onDelete('cascade'); // Links to cases table
            $table->string('record_name'); // Record category
            $table->string('question_label'); // Question label
            $table->text('answer')->nullable(); // Actual answer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaster.case_answers');

       
    }
};
