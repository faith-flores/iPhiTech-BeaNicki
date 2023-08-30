<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('profile_id');

            $table->string('title');
            $table->mediumText('description');
            $table->string('identifier', 255);

            $table->unsignedBigInteger('hours_to_work_id')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->unsignedBigInteger('skill_level_id')->nullable();
            $table->unsignedBigInteger('type_of_work_id')->nullable();
            $table->unsignedBigInteger('duration_id')->nullable();

            $table->integer('total_hire_count')->nullable();
            $table->decimal('salary')->default(0);
            $table->date('start_date')->nullable();
            $table->date('interview_availability')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->mediumText('notes')->nullable();
            $table->mediumText('internal_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
