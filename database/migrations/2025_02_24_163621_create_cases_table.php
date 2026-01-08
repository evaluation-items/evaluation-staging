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
        Schema::table('imaster.cases', function (Blueprint $table) {
            $table->id();
            $table->string('title'); 
            $table->string('case_id')->unique(); // Unique case identifier from .csdb
            $table->string('person_id')->nullable(); // Stores person ID
            $table->string('form_number')->nullable(); // Stores form number
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaster.cases');
       
    }
};
