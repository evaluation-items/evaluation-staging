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
        Schema::create('imaster.cspro_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_id');
            $table->foreign('t_id')->references('t_id')->on('imaster.cspro_title')->onDelete('cascade');
            $table->string('case_id');
            $table->string('person_id');
            $table->string('form_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaster.cspro_cases');
    }
};
