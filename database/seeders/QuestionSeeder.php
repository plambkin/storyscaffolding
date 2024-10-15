<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            ['component_type' => 'Descriptive', 'question_text' => 'Describe a lake as seen by a young man who has just committed murder. Do not mention the murder.'],
            ['component_type' => 'Descriptive', 'question_text' => 'Describe a landscape as seen by a bird. Do not mention the bird.'],
            ['component_type' => 'Descriptive', 'question_text' => 'Describe a landscape as seen by an old woman whose disgusting and detestable old husband has just died. Do not mention the husband or death'],
            ['component_type' => 'Descriptive', 'question_text' => 'Describe and evoke a simple action (for example, sharpening a pencil, carving a tombstone, shooting a rat)'],
            ['component_type' => 'PoV', 'question_text' => 'Write a novel opening, on any subject, in which the point of view is third person objective. Write a short-story opening in the same point of view.'],
            ['component_type' => 'Dialogue', 'question_text' => 'Write a dialogue in which each of the two characters has a secret. Do not reveal the secret but make the reader intuit it. For example. the dialogue might be between a husband, who has lost his job and hasnt worked up the courage to tell his wife, and his wife, who has a lover in the bedroom'],
            ['component_type' => 'Dialogue', 'question_text' => 'Write a dialogue in which each of the two characters has a secret. Do not reveal the secret but make the reader intuit it. For example. the dialogue might be between a husband, who has lost his job and hasnt worked up the courage to tell his wife, and his wife, who has a lover in the bedroom'],
            ['component_type' => 'Plot', 'question_text' => 'Develop the plot of a short story'],
            ['component_type' => 'Plot', 'question_text' => 'Plot an energetic novel'],
            ['component_type' => 'PoV', 'question_text' => 'Write a brief sketch in the essayist-omniscient voice'],
             ['component_type' => 'Style', 'question_text' => 'Write a short piece of fiction in mixed prose and verse'],

            ['component_type' => 'Character', 'question_text' =>'Create a character profile for a protagonist, including their background, traits, and motivations. Then, outline their character arc, detailing how they change throughout the story. Highlight key challenges, internal conflicts, and the resolution of their journey.'],




            // Add more questions...
        ];


        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
