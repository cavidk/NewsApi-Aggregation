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
        Schema::create('guardian_news', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('sectionId');
            $table->string('sectionName');
            $table->dateTime('webPublicationDate');
            $table->string('webTitle');
            $table->text('webUrl');
            $table->text('apiUrl');
            $table->boolean('isHosted');
            $table->text('pillarId');
            $table->string('pillarName');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guardian_news');
    }
};
