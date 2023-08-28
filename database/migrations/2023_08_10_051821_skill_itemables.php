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
        Schema::create('skill_itemables', function (Blueprint $table) {
            $table->unsignedBigInteger('skill_item_id');
            $table->morphs('skill_itemable');
            $table->integer('rating')->default(1);
            // $table->foreign('skill_item_id')->references('id')->on('skill_items')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_item_relations');
    }
};
