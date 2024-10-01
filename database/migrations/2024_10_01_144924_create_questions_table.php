<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
{
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->string('component_type');
        $table->text('question_text');
        $table->timestamps();
    });

    // Insert pre-defined questions data if needed
    DB::table('questions')->insert([
        ['component_type' => 'Descriptive', 'question_text' => 'Describe a lake as seen by a young man who has just committed murder. Do not mention the murder.', 'created_at' => now(), 'updated_at' => now()],
        ['component_type' => 'Descriptive', 'question_text' => 'Describe a landscape as seen by a bird. Do not mention the bird.', 'created_at' => now(), 'updated_at' => now()],
        // Add the rest of your questions here
    ]);
}

public function down()
{
    Schema::dropIfExists('questions');
}

}
