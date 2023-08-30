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
        Schema::create('profiles_accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('owner_user_id');
            $table->unsignedTinyInteger('account_type')->default(0);
            $table->string('company_name', 100)->nullable();
            $table->string('email', 100);
            $table->string('company_phone',100)->nullable();
            $table->string('web_url', 1024)->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_multi_user')->default(false);
            $table->unsignedBigInteger('client_status_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
