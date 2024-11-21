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
        Schema::table('quiz_question_answers', function (Blueprint $table) {
            $table->renameColumn('question_id', 'quiz_question_id');
        });
    }

    public function down()
    {
        Schema::table('quiz_question_answers', function (Blueprint $table) {
            $table->renameColumn('quiz_question_id', 'question_id');
        });
    }
};
