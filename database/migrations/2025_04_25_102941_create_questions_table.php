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
        Schema::create('imaster.questions', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('t_id');
            $table->foreign('t_id')->references('t_id')->on('imaster.cspro_title')->onDelete('cascade');
            $table->text('question'); // Full question label (e.g., "જાતિ")
            $table->string('item_name'); // Item name (e.g., Q_8)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaster.questions');
    }
};
