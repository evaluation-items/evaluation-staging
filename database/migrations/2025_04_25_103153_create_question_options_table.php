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
        Schema::create('imaster.question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('imaster.questions')->onDelete('cascade');
            $table->unsignedBigInteger('t_id');
            $table->foreign('t_id')->references('t_id')->on('imaster.cspro_title')->onDelete('cascade');
            $table->string('options');
            $table->string('values');
            $table->softDeletes();
            $table->timestamps();
        
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaster.question_options');
    }
};
