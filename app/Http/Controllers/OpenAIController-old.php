<?php

// app/Http/Controllers/OpenAIController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Client;
use App\Models\Question;

class OpenAIController extends Controller
{
    protected $openai;

    public function __construct(Client $openai)
    {
        $this->openai = $openai;
    }

    public function generateQuestion(Request $request)
    {
        // Log the incoming request
        Log::info('generateQuestion request:', $request->all());

        try {
            $componentType = $request->input('component_type');

            // Log the component type
            Log::info('Component type:', ['component_type' => $componentType]);

            // Fetch the question from the database based on the component type
            $question = Question::where('component_type', $componentType)->inRandomOrder()->first();

            if (!$question) {
                return response()->json(['error' => 'No question found for the specified component type'], 404);
            }

            $prompt = "Assuming that I am only beginner as a writer, give me a question similar to: " . $question->question_text;

            // Example call to OpenAI API using the gpt-3.5-turbo model
            $response = $this->openai->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            // Convert the response to an array for logging
            $responseArray = $response->toArray();

            // Log the response from OpenAI
            Log::info('OpenAI response:', $responseArray);

            return response()->json([
                'question' => $responseArray['choices'][0]['message']['content']
            ]);
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error generating question:', ['error' => $e->getMessage()]);

            // Check if the error is due to exceeding quota
            if (strpos($e->getMessage(), 'You exceeded your current quota') !== false) {
                return response()->json(['error' => 'You have exceeded your API quota. Please check your OpenAI plan and billing details.'], 429);
            }

            return response()->json(['error' => 'Unable to generate question'], 500);
        }
    }
}