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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('account_id');
            /**
             * TODO: relate profile_id
             */
            $table->string('title');
            $table->mediumText('description');
            $table->string('working_hours');
            $table->integer('schedule_id');
            $table->integer('type_of_work_id');
            $table->integer('skill_level_id');
            $table->integer('total_hire_count')->nullable();
            $table->decimal('salary')->default(0);
            $table->date('start_date')->nullable();
            $table->date('interview_availability')->nullable();
            $table->unsignedTinyInteger('status')->nullable();

            $table->foreign('account_id')
                ->references('id')
                ->on('profiles_accounts')
                ->onUpdate('NO ACTION')
                ->onDelete('RESTRICT')
            ;
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
