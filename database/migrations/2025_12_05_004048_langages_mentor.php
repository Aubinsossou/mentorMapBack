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
         Schema::create('langages_mentor', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('mentors_id');
            $table->foreign('mentors_id')->references('id')->on('mentors');
             $table->unsignedBigInteger('langages_id');
            $table->foreign('langages_id')->references('id')->on('langages');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('langages_mentor');
        
    }
};
