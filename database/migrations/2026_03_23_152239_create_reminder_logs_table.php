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
        Schema::create('imaster.reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stage_id');
            $table->string('stage_type'); // e.g., 'stage_1', 'stage_2'
            $table->string('reminder_type'); // 'before_7_days', 'current_day', 'after_7_days'
            $table->string('recipient_email');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['stage_id', 'stage_type', 'reminder_type']); // Fast querying for reports
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminder_logs');
    }
};
