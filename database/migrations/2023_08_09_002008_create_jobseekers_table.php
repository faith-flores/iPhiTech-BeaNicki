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
        Schema::create('jobseekers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('nickname', 100)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('phone_number', 25)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('job_title')->nullable();
            $table->mediumText('skills_summary')->nullable();
            $table->string('experience', 255)->nullable();
            $table->string('website_url', 255)->nullable();
            $table->foreignId('gender_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->foreignId('desired_salary_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->foreignId('education_attainment_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->foreignId('employment_status_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->foreignId('hours_to_work_id')->nullable()->constrained('picklist_items')->nullOnDelete();
            $table->boolean('is_profile_completed')->default(false);
            $table->integer('status')->default(1);

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobseekers');
    }
};