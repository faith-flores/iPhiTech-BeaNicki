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
        Schema::create('skill_items', function(Blueprint $table)
        {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('skill_id')->nullable();
            $table->string('label', 50)->index();
            $table->text('description')->nullable();
            $table->string('identifier', 100);
            $table->integer('sequence');
            $table->boolean('status');
            $table->json('meta')->nullable();

            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::drop('skill_items');
    }
};
