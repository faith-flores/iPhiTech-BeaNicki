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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('account_id')->constrained('profiles_accounts')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('client_manager_id')->nullable();
            $table->foreignId('billing_id')->nullable()->refer('profiles_billing')->nullOnDelete();

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone_number', 20);

            $table->boolean('is_profile_completed')->default(false);
            $table->integer('status')->default(1);

            $table->softDeletes();

            $table->foreign('client_manager_id')
                ->references('id')
                ->on('users')
                ->onUpdate('NO ACTION')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
