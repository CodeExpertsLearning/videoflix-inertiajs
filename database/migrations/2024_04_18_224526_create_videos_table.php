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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('content_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->uuid('code');

            $table->string('name');
            $table->string('description')->nullable();

            //Inicialmente recebe o path do video subido / depois do video processado...
            $table->string('video')->nullable();

            $table->string('thumb')->nullable();
            $table->string('slug');

            $table->boolean('is_processed')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
