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
        Schema::create('imaster.cspro_data', function (Blueprint $table) {
            $table->id();
            $table->integer('record_id')->unique();
            $table->string('name');
            $table->integer('age');
            $table->string('gender');
            $table->string('location');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaster.cspro_data');
    }
};
