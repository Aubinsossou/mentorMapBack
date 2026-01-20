<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demande_mentors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mentors_id');
            $table->foreign('mentors_id')->references('id')->on('mentors');
            $table->unsignedBigInteger('mentoree_id');
            $table->foreign('mentoree_id')->references('id')->on('mentorees');
            $table->string("status");
            $table->unique(["mentors_id","mentoree_id"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_mentors');
    }
};
