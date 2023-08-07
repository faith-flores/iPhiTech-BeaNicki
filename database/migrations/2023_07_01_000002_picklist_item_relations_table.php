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
        Schema::create('picklist_item_relations', function(Blueprint $table)
        {
            $table->unsignedBigInteger('picklist_item_id');
            $table->morphs('relatable');
            $table->foreign('picklist_item_id')->references('id')->on('picklist_items')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picklist_items');
    }
};
