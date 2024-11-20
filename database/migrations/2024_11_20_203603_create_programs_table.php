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
        Schema::create('programms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consultant_id'); // Consultant who created the program
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
    
            $table->foreign('consultant_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programms');
    }
};
