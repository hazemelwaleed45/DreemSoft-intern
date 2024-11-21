<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->integer('score')->default(0)->change(); // Add default value
        });
    }
    
    public function down()
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->integer('score')->default(null)->change(); // Revert to no default value
        });
    }
};
