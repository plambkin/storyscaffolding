<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExerciseTypeToSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('exercise_type')->after('textarea3');
        });
    }

    public function down()
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('exercise_type');
        });
    }
}

