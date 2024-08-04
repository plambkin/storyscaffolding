<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Correctly import the Log facade
use OpenAI;
use App\Models\Question;



class OpenAIController extends Controller
{
    protected $openai;


    public function __construct()
{
    $apiKey = env('OPENAI_API_KEY');

    // Log the API key or its absence
    if ($apiKey) {
        Log::info('OPENAI_API_KEY retrieved successfully. Length: ' . strlen($apiKey));
        // Optionally, log a partial key for more clarity
        Log::info('OPENAI_API_KEY starts with: ' . substr($apiKey, 0, 5) . '...');
    } else {
        Log::warning('OPENAI_API_KEY is not set or not found in the .env file.');
    }

    // Initialize the OpenAI client
    $this->openai = OpenAI::Client($apiKey);
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

            $prompt = "Assuming that I am only a beginner as a writer, give me a question similar to: " . $question->question_text . " .Please pretend there is not AI or another person on the other side of the question. This means, don't preface it with a line such as Sure! Here's a question for you.";

            // Example call to OpenAI API using the gpt-4 model
            $response = $this->openai->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
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

    public function generateFeedback(Request $request)
    {
        Log::info('generateFeedback request:', $request->all());

        try {
            $prompt = $request->input('prompt');
            $text1 = $request->input('text1');
            $text2 = $request->input('text2');

            Log::info('Prompt:', ['prompt' => $prompt]);
            Log::info('Text1:', ['text1' => $text1]);
            Log::info('Text2:', ['text2' => $text2]);

            $fullPrompt = "$prompt\n\nQuestion: $text1\nAnswer: $text2";

            $response = $this->openai->chat()->create([
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an expert in how Ernest Hemingway writes.Please pretend there is not AI or another person on the other side of the question'],
                    ['role' => 'user', 'content' => $fullPrompt],
                ],
            ]);

            $responseArray = $response->toArray();
            Log::info('OpenAI response:', $responseArray);

            return response()->json([
                'feedback' => $responseArray['choices'][0]['message']['content']
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating feedback:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            if (strpos($e->getMessage(), 'You exceeded your current quota') !== false) {
                return response()->json(['error' => 'You have exceeded your API quota. Please check your OpenAI plan and billing details.'], 429);
            }
            return response()->json(['error' => 'Unable to generate feedback'], 500);
        }
    }
}
