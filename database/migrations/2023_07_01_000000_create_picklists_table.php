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
        Schema::create('picklists', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('label', 255)->index();
            $table->text('description')->nullable();
            $table->string('identifier', 100);
            $table->integer('default_item')->nullable();
            $table->boolean('is_tag')->nullable();
            $table->boolean('is_system');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('picklists');
    }
};
