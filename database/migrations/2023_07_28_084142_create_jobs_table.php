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

            $table->foreignId('account_id')->constrained('profiles_accounts')->restrictOnDelete();
            $table->foreignId('profile_id')->constrained('profiles')->restrictOnDelete();

            $table->string('title');
            $table->mediumText('description');
            $table->string('working_hours');
            $table->foreignId('schedule_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->foreignId('skill_level_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->foreignId('type_of_work_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->integer('total_hire_count')->nullable();
            $table->decimal('salary')->default(0);
            $table->date('start_date')->nullable();
            $table->date('interview_availability')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
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
