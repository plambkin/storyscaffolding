<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use App\Models\User;
use PDF; // Using Barryvdh\DomPDF
use Illuminate\Support\Facades\Log; // Importing the Log facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;




class SubmissionController extends Controller
{
    public function show($userId)
    {
        Log::info('Showing submissions for user ID: ' . $userId);

        // Retrieve all submissions by the user ID
        $submissions = Submission::where('user_id', $userId)->get();
        $user = User::findOrFail($userId);
        Log::info('User retrieved', ['user_id' => $user->id, 'user_name' => $user->name]);

        return view('submissions', compact('submissions', 'user'));
    }



    public function exportPdf(Request $request)
    {
        Log::info('Export to PDF request received for user ID: ' . $request->input('user_id'));

        // Gather all submissions for the user, ordered by most recent first
        $user = User::findOrFail($request->input('user_id'));
        $submissions = Submission::where('user_id', $user->id)
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->get();

        // Format each submission date
        $submissions->each(function($submission) {
            $submission->formatted_date = Carbon::parse($submission->created_at)->format('jS M Y');
        });

        // Prepare the data for the PDF
        $data = [
            'user_name' => $user->name,
            'submissions' => $submissions,
        ];

        Log::info('Data prepared for PDF', ['user_name' => $user->name, 'submissions_count' => $submissions->count()]);

        try {
            // Generate the PDF
            $pdf = PDF::loadView('pdf.submission', $data);
            Log::info('PDF generated successfully');
            
            return $pdf->download('submissions.pdf');
        } catch (\Exception $e) {
            Log::error('Error generating PDF', ['error' => $e->getMessage()]);
            return back()->with('error', 'There was an error generating the PDF.');
        }
    }



        public function store(Request $request)
    {
        // Log the incoming request data
        Log::info('SubmissionController@store - Request received:', $request->all());

        // Validate the request data
        $validatedData = $request->validate([
            'textarea1' => 'required|string',
            'textarea2' => 'required|string',
            'textarea3' => 'nullable|string', // Allow textarea3 to be null
            'exercise_type' => 'required|string',
            'grade' => 'required|numeric|min:1|max:10',
        ]);


        try {
            // Add the authenticated user's ID to the validated data
            $validatedData['user_id'] = Auth::id(); // or auth()->id();

            // Log the validated data
            Log::info('SubmissionController@store - Validated data:', $validatedData);

            // Save the submission
            $submission = Submission::create($validatedData);

            // Update the user's score based on the exercise_type
            $user = User::find(Auth::id());

            switch ($validatedData['exercise_type']) {
                case 'Descriptive':
                    $user->description_score += $validatedData['grade'];
                    break;
                case 'Dialogue':
                    $user->dialogue_score += $validatedData['grade'];
                    break;
                case 'Character':
                    $user->character_score += $validatedData['grade'];
                    break;
                case 'Plot':
                    $user->plot_score += $validatedData['grade'];
                    break;
                default:
                    Log::warning('SubmissionController@store - Unrecognized exercise type:', ['exercise_type' => $validatedData['exercise_type']]);
                    break;
            }

            $user->save();

            // Log the successful save
            Log::info('SubmissionController@store - Submission saved successfully:', ['submission_id' => $submission->id]);

            // Redirect to the dashboard with a success message
            return redirect()->route('dashboard')->with('success', 'Submission saved successfully.');
        } catch (\Exception $e) {
            // Log the error with the exception message and stack trace
            Log::error('SubmissionController@store - Error saving submission:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Redirect back with an error message
            return redirect()->back()->withErrors('An error occurred while saving your submission. Please try again.');
        }
    }

}






