<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Submission;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        // Log incoming request data
        Log::info('Store method called with request data:', $request->all());

        // Validate request data
        $validated = $request->validate([
            'textarea1' => 'required|string',
            'textarea2' => 'required|string',
            'textarea3' => 'required|string',
            'exercise_type' => 'required|string', // Added validation for exercise_type
        ]);

        // Log validated data
        Log::info('Validated data:', $validated);

        // Attempt to create the submission
        try {
            $submission = Submission::create([
                'user_id' => Auth::id(),
                'textarea1' => $request->textarea1,
                'textarea2' => $request->textarea2,
                'textarea3' => $request->textarea3,
                'exercise_type' => $request->exercise_type, // Save exercise_type
            ]);

            // Log successful creation
            Log::info('Submission created successfully:', $submission->toArray());

            return redirect()->back()->with('success', 'Submission saved successfully!');
        } catch (\Exception $e) {
            // Log any exception that occurs
            Log::error('Error creating submission:', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'There was an error saving your submission. Please try again.');
        }
    }
}



