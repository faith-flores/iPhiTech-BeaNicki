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
        Schema::create('jobseekers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('user_id');

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
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->unsignedBigInteger('desired_salary_id')->nullable();
            $table->unsignedBigInteger('education_attainment_id')->nullable();
            $table->unsignedBigInteger('employment_status_id')->nullable();
            $table->unsignedBigInteger('hours_to_work_id')->nullable();

            $table->boolean('is_profile_completed')->default(false);
            $table->integer('status')->default(1);

            $table->integer('iq')->nullable();
            $table->string('english')->nullable();
            $table->string('disc_dominance_score')->nullable();
            $table->string('disc_dominance_url')->nullable();
            $table->string('disc_influence_score')->nullable();
            $table->string('disc_influence_url')->nullable();
            $table->string('disc_compliance_score')->nullable();
            $table->string('disc_compliance_url')->nullable();
            $table->string('disc_steadiness_score')->nullable();
            $table->string('disc_steadiness_url')->nullable();

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
