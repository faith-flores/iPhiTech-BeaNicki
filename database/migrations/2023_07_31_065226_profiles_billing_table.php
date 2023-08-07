<?php

use App\Models\Profile;
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
        Schema::create('profiles_billing', function(Blueprint $table)
        {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('account_id')->constrained('profiles_accounts')->restrictOnDelete();

            $table->string('company_name', 255);
            $table->string('invoice_name', 255);
            $table->string('email', 100);
            $table->tinyInteger('billing_type')->default(0);
            $table->boolean('is_default')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('profiles_billing');
    }
};
