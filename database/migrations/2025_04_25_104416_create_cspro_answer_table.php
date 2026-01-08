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
        Schema::create('imaster.cspro_answer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cased_id');
            $table->foreign('cased_id')->references('id')->on('imaster.cspro_cases')->onDelete('cascade');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('imaster.questions')->onDelete('cascade');
            $table->unsignedBigInteger('t_id');
            $table->foreign('t_id')->references('t_id')->on('imaster.cspro_title')->onDelete('cascade');
            $table->text('answers')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaster.cspro_answer');
    }
};
