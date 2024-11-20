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
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('day');
        });
    
     
        Schema::table('schedules', function (Blueprint $table) {
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
                  ->after('consultant_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn('day');
        });

 
        Schema::table('schedules', function (Blueprint $table) {
            $table->date('day')->after('consultant_id'); 
        });
    }
};
