<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenAI;

class AssessmentController extends Controller
{
    /**
     * Show the exercise page.
     *
     * @return \Illuminate\View\View
     */
    public function showExercise()
{
    $userId = Auth::id();
    Log::info("Starting showExercise method for user {$userId}");

    // Retrieve the user and the current assessment number from the User table
    $user = Auth::user();
    $currentAssessmentNo = $user->assessment_no ?? 1; // Default to 1 if null
    Log::info("User {$userId} - Retrieved current assessment_no: {$currentAssessmentNo}");

    // Define the required components
   
    $requiredComponents = [
        'Descriptive',
        'Dialogue',
        // 'Plot/Structure',  // Temporarily disabled
        // 'Style',           // Temporarily disabled
        // 'Point of View',    // Temporarily disabled
        'Character'
    ];

    Log::info("User {$userId} - Required components defined: " . implode(', ', $requiredComponents));

    // Get all completed exercise types for the user under the current assessment number
    $completedComponents = Submission::where('user_id', $userId)
        ->where('assessment_no', $currentAssessmentNo)
        ->pluck('exercise_type')
        ->toArray();
    Log::info("User {$userId} - Completed components for assessment_no {$currentAssessmentNo}: " . implode(', ', $completedComponents));

    // Check if all required components have been completed and if they have not been graded
    $allComponentsCompleted = count(array_unique($completedComponents)) === count($requiredComponents);
    $allSubmissionsGraded = Submission::where('user_id', $userId)
        ->where('assessment_no', $currentAssessmentNo)
        ->where('graded', true)
        ->exists();
    Log::info("User {$userId} - All components completed: " . ($allComponentsCompleted ? 'Yes' : 'No'));
    Log::info("User {$userId} - All submissions graded: " . ($allSubmissionsGraded ? 'Yes' : 'No'));

    if ($allComponentsCompleted && !$allSubmissionsGraded) {
        Log::info("User {$userId} - Triggering grading for assessment_no {$currentAssessmentNo}");
        
        // Grade all submissions and set the `graded` flag to true
        $this->gradeAllSubmissions($userId, $currentAssessmentNo);

        // Call the completed method directly to finalize the process
        //$this->completed();

        // Increment the assessment number and assessment count for the user
        $user->assessment_no = $currentAssessmentNo + 1;
        $user->assessment_count += 1;
        $user->save();
        Log::info("User {$userId}'s assessment_count incremented to {$user->assessment_count} and assessment_no set to {$user->assessment_no}");

        // Update the current assessment number after increment
        $currentAssessmentNo = $user->assessment_no;

        // Generate feedback based on the user's scores
        $improvementFeedback = $this->provideImprovementFeedback($user);

        // Log the feedback generated
        Log::info("Improvement feedback generated for user {$user->id}: {$improvementFeedback}");

        // Reset completed components for the new assessment
        $completedComponents = [];
        Log::info("User {$userId} - Reset completed components for new assessment_no {$currentAssessmentNo}");

        // Return the view for the completed exercise with feedback data
        return view('assessment.completed', compact('user', 'improvementFeedback'));

    }

    // Define the exercise questions
    $exerciseData = [
        'Descriptive' => "Describe the village at dawn just before the fog begins to lift. Focus on the sensory details—what does the village look, smell, and sound like? How does the atmosphere change as the fog recedes?",
        'Dialogue' => "Write a conversation between two villagers discussing the origins of the mysterious fog. One villager is skeptical and dismissive, while the other is deeply superstitious and believes the fog is a bad omen.",
        'Plot/Structure' => "Outline a plot where a stranger arrives in the village during the fog and brings with them a secret that could either save or doom the village. What is the inciting incident? How does the plot develop, and what is the climax?",
        'Style' => "Write a short paragraph in two different styles: one in a sparse, Hemingway-like style, and the other in a more ornate, descriptive style. Use the same scene—a villager walking through the fog—but convey it differently with each style.",
        'Point of View' => "Rewrite the scene where the stranger arrives in the village from two different points of view: first-person (from the perspective of the stranger) and third-person limited (from the perspective of a village elder who suspects the stranger’s intentions).",
        'Character' => "Create a detailed character profile for one of the villagers. Include their background, personality traits, motivations, and how they interact with the mysterious fog."
    ];
    Log::info("User {$userId} - Defined exercise questions");

    // Filter out the exercise types that have already been completed for the current assessment number
    $remainingExerciseTypes = array_diff(array_keys($exerciseData), $completedComponents);
    Log::info("User {$userId} - Remaining exercise types for assessment_no {$currentAssessmentNo}: " . implode(', ', $remainingExerciseTypes));

    // If there are no remaining exercises, redirect to a completion page or similar
    if (empty($remainingExerciseTypes)) {
        Log::info("User {$userId} - No remaining exercise types, redirecting to completion page");
        return redirect()->route('assessment.completed')->with('success', 'Assessment completed and graded. Please start a new assessment.');
    }

    Log::info("User {$userId} - Showing exercise page with remaining exercise types");
    return view('exercise', compact('exerciseData', 'remainingExerciseTypes', 'currentAssessmentNo'));
}


    /**
     * Handle the exercise submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitExercise(Request $request)
    {
        Log::info('Form data received:', $request->all());

        $userId = Auth::id();

        $user = Auth::user();
        $latestAssessmentNo = $user->assessment_no ?? 1;

        Log::info("User {$userId} - Latest assessment number retrieved: {$latestAssessmentNo}");

        // Check if the user already has a submission for this exercise type and assessment number
        $existingSubmission = Submission::where('user_id', $userId)
            ->where('exercise_type', $request->exercise_type)
            ->where('assessment_no', $latestAssessmentNo)
            ->first();

        if ($existingSubmission) {
            Log::warning("User {$userId} already has a submission for exercise type '{$request->exercise_type}' under assessment number '{$latestAssessmentNo}'. Redirecting back with error.");
            return redirect()->route('assessment.show')->with('error', 'You have already submitted an answer for this exercise type in the current assessment.');
        }

        // Create a new submission entry in the database
        Submission::create([
            'user_id' => $userId,
            'assessment_no' => $latestAssessmentNo,
            'textarea1' => $request->textarea1,
            'textarea2' => $request->textarea2,
            'textarea3' => $request->textarea3,
            'exercise_type' => $request->exercise_type,
            'graded' => false // No grading yet
        ]);

        // After all components are submitted, show "Submit for Feedback" button
        return redirect()->route('assessment.show')->with('success', 'Exercise submitted successfully!');
    }

    /**
     * Check if all components are submitted and trigger grading.
     *
     * @param  int  $userId
     * @param  int  $assessmentNo
     * @return bool
     */
    protected function checkAndGradeSubmissions($userId, $assessmentNo)
    {
        Log::info("Starting checkAndGradeSubmissions method for user {$userId}, assessment_no {$assessmentNo}");

        // Define the required components for an assessment
        $requiredComponents = ['Descriptive', 'Dialogue', 'Plot/Structure', 'Style', 'Point of View', 'Character'];
        Log::info("Required components for grading: " . implode(', ', $requiredComponents));

        // Retrieve completed components for the given assessment number
        $completedComponents = Submission::where('user_id', $userId)
            ->where('assessment_no', $assessmentNo)
            ->pluck('exercise_type')
            ->toArray();
        Log::info("User {$userId}, assessment_no {$assessmentNo} - Completed components: " . implode(', ', $completedComponents));

        // Check if all required components are completed
        $completedCount = count(array_unique($completedComponents));
        $requiredCount = count($requiredComponents);
        Log::info("User {$userId}, assessment_no {$assessmentNo} - Completed count: {$completedCount}, Required count: {$requiredCount}");

        if ($completedCount === $requiredCount) {
            Log::info("User {$userId}, assessment_no {$assessmentNo} - All required components completed. Proceeding to grade all submissions.");
            // All components are completed, proceed to grade
            $this->gradeAllSubmissions($userId, $assessmentNo);
            Log::info("User {$userId}, assessment_no {$assessmentNo} - Grading completed.");
            return true;
        }

        Log::info("User {$userId}, assessment_no {$assessmentNo} - Not all components are completed. Grading not triggered.");
        return false;
    }


    /**
     * Grade all submissions for the user.
     *
     * @param  int  $userId
     * @param  int  $assessmentNo
     * @return void
     */
    protected function gradeAllSubmissions($userId, $assessmentNo)
    {
        $submissions = Submission::where('user_id', $userId)
            ->where('assessment_no', $assessmentNo)
            ->get();

        $grades = [];

        foreach ($submissions as $submission) {
            if (is_null($submission->grade)) {
                // Generate grading prompt and get the grade
                $gradePrompt = $this->generateGradingPrompt($submission);
                $grade = $this->getChatGPTGrade($gradePrompt);

                // Generate feedback prompt and get the feedback
                $feedbackPrompt = $this->generateFeedbackPrompt($submission);
                $feedback = $this->getChatGPTFeedback($feedbackPrompt);

                // Update submission with the grade and feedback, and set `graded` to true
                $submission->grade = $grade;
                $submission->textarea3 = $feedback;
                $submission->graded = true;
                $submission->save();

                $grades[$submission->exercise_type] = $grade;

                // Log the successful update of submission
                Log::info("Updated submission ID {$submission->id} with grade {$grade} and feedback.");
            }
        }

        // Update user's scores based on grades
        $user = Auth::user();
        $user->descriptive_score = $grades['Descriptive'] ?? $user->descriptive_score;
        $user->dialogue_score = $grades['Dialogue'] ?? $user->dialogue_score;
        $user->plot_score = $grades['Plot/Structure'] ?? $user->plot_score;
        $user->style_score = $grades['Style'] ?? $user->style_score;
        $user->pov_score = $grades['Point of View'] ?? $user->pov_score;
        $user->character_score = $grades['Character'] ?? $user->character_score;

        // Increment assessment number

        $user->assessment_no += 1;


        $user->save();


        // Log the successful update of user scores and assessment number
        Log::info("Updated user ID {$user->id} scores based on submission grades and incremented assessment_no to {$user->assessment_no}.");

    }




    /**
     * Generate the grading prompt based on the exercise type.
     *
     * @param  \App\Models\Submission  $submission
     * @return string
     */
    protected function generateGradingPrompt(Submission $submission)
    {
        Log::info("Generating grading prompt for submission ID {$submission->id}, exercise_type {$submission->exercise_type}");

        $prompt = '';

        switch ($submission->exercise_type) {
            case 'Descriptive':
                $prompt = "Grade the following description out of 10: {$submission->textarea2}. Consider clarity, vividness, and sensory engagement.";
                Log::info("Generated prompt for 'Descriptive': {$prompt}");
                break;
            case 'Dialogue':
                $prompt = "Grade the following dialogue out of 10: {$submission->textarea2}. Consider characterization, natural flow, and subtext.";
                Log::info("Generated prompt for 'Dialogue': {$prompt}");
                break;
            case 'Plot/Structure':
                $prompt = "Grade the following plot/structure outline out of 10: {$submission->textarea2}. Consider coherence, pacing, and narrative arc.";
                Log::info("Generated prompt for 'Plot/Structure': {$prompt}");
                break;
            case 'Style':
                $prompt = "Grade the following writing style out of 10: {$submission->textarea2}. Consider adaptability, tone, and effectiveness in conveying the scene.";
                Log::info("Generated prompt for 'Style': {$prompt}");
                break;
            case 'Point of View':
                $prompt = "Grade the following point of view out of 10: {$submission->textarea2}. Consider consistency, depth of perspective, and impact on the narrative.";
                Log::info("Generated prompt for 'Point of View': {$prompt}");
                break;
            case 'Character':
                $prompt = "Grade the following character profile out of 10: {$submission->textarea2}. Consider depth, personality, and how the character interacts with the story.";
                Log::info("Generated prompt for 'Character': {$prompt}");
                break;
            default:
                Log::warning("Unknown exercise_type '{$submission->exercise_type}' for submission ID {$submission->id}");
                $prompt = "No valid prompt available.";
                break;
        }

        Log::info("Final generated prompt for submission ID {$submission->id}: {$prompt}");
        return $prompt;
    }


    /**
     * Get the grade from ChatGPT based on the prompt.
     *
     * @param  string  $prompt
     * @return int
     */
    protected function getChatGPTGrade($prompt)
    {
        Log::info("Initializing OpenAI client for grading.");

        try {
            // Initialize the OpenAI client

            $apiKey = config('app.openai_api_key');

            $client = OpenAI::client($apiKey);

            Log::info("OpenAI client initialized successfully.");

            // Log the prompt being sent to OpenAI
            Log::info("Sending prompt to OpenAI for grading: {$prompt}");

            // Create a chat completion request with a more explicit instruction to return a numerical grade
            $response = $client->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a grading assistant. Grade the following text based on clarity, structure, and creativity, and return a score out of 10. Only provide the numerical score and nothing else.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 10, // Reduce max_tokens to minimize the chance of incomplete responses
            ]);

            Log::info("Received response from OpenAI: " . json_encode($response));

            // Extract the grade from the response
            $gradeText = trim($response['choices'][0]['message']['content']);
            $grade = floatval($gradeText); // Use floatval to handle decimal values

            // Log the extracted grade
            Log::info("Extracted grade: {$grade} from response content: '{$gradeText}'");

            // Ensure the grade is within the expected range
            if ($grade < 0.0 || $grade > 10.0) {
                throw new \Exception("Invalid grade received: {$gradeText}");
            }

        } catch (\Exception $e) {
            Log::error("Error while communicating with OpenAI: " . $e->getMessage());
            // Return a default grade in case of failure
            $grade = 0;
        }

        return $grade;
    }


    protected function getChatGPTFeedback($prompt)
    {
        Log::info("Initializing OpenAI client for feedback.");

        try {
            // Initialize the OpenAI client
            $apiKey = env('OPENAI_API_KEY'); // Use environment variables for security
            $client = OpenAI::client($apiKey);

            Log::info("OpenAI client initialized successfully for feedback.");

            // Log the prompt being sent to OpenAI
            Log::info("Sending prompt to OpenAI for feedback: {$prompt}");

            // Create a chat completion request for feedback
            $response = $client->chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a feedback assistant. Provide detailed feedback on the following text based on clarity, structure, creativity, and adherence to the prompt.',
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 200, // Adjust as needed to get comprehensive feedback
            ]);

            Log::info("Received response from OpenAI: " . json_encode($response));

            // Extract the feedback from the response
            $feedback = trim($response['choices'][0]['message']['content']);

            // Log the extracted feedback
            Log::info("Extracted feedback: '{$feedback}'");

        } catch (\Exception $e) {
            Log::error("Error while communicating with OpenAI for feedback: " . $e->getMessage());
            // Return a default feedback in case of failure
            $feedback = 'Feedback could not be generated due to an error. Please try again later.';
        }

        return $feedback;
    }


    protected function generateFeedbackPrompt(Submission $submission)
    {
        $prompt = '';

        switch ($submission->exercise_type) {
            case 'Descriptive':
                $prompt = "Provide feedback on the following description: {$submission->textarea2}. Focus on clarity, vividness, and sensory engagement.";
                break;
            case 'Dialogue':
                $prompt = "Provide feedback on the following dialogue: {$submission->textarea2}. Focus on characterization, natural flow, and subtext.";
                break;
            case 'Plot/Structure':
                $prompt = "Provide feedback on the following plot/structure outline: {$submission->textarea2}. Focus on coherence, pacing, and narrative arc.";
                break;
            case 'Style':
                $prompt = "Provide feedback on the following writing style: {$submission->textarea2}. Focus on adaptability, tone, and effectiveness in conveying the scene.";
                break;
            case 'Point of View':
                $prompt = "Provide feedback on the following point of view: {$submission->textarea2}. Focus on consistency, depth of perspective, and impact on the narrative.";
                break;
            case 'Character':
                $prompt = "Provide feedback on the following character profile: {$submission->textarea2}. Focus on depth, personality, and how the character interacts with the story.";
                break;
        }

        return $prompt;
    }




    public function getRemainingExercises()
    {
        $userId = Auth::id();
        Log::info("Starting getRemainingExercises method for user {$userId}");

        // Retrieve the current assessment number from the User table
        $user = Auth::user();
        $latestAssessmentNo = $user->assessment_no ?? 1; // Default to 1 if null
        Log::info("User {$user->id} - Retrieved current assessment_no from User table: {$latestAssessmentNo}");

        // Define the exercise questions
        $exerciseData = [
            'Descriptive' => "Describe the village at dawn just before the fog begins to lift. Focus on the sensory details—what does the village look, smell, and sound like? How does the atmosphere change as the fog recedes?",
            'Dialogue' => "Write a conversation between two villagers discussing the origins of the mysterious fog. One villager is skeptical and dismissive, while the other is deeply superstitious and believes the fog is a bad omen.",
           // 'Plot/Structure' => "Outline a plot where a stranger arrives in the village during the fog and brings with them a secret that could either save or doom the village. What is the inciting incident? How does the plot develop, and what is the climax?",
           // 'Style' => "Write a short paragraph in two different styles: one in a sparse, Hemingway-like style, and the other in a more ornate, descriptive style. Use the same scene—a villager walking through the fog—but convey it differently with each style.",
          //  'Point of View' => "Rewrite the scene where the stranger arrives in the village from two different points of view: first-person (from the perspective of the stranger) and third-person limited (from the perspective of a village elder who suspects the stranger’s intentions).",
            'Character' => "Create a detailed character profile for one of the villagers. Include their background, personality traits, motivations, and how they interact with the mysterious fog."
        ];
        Log::info("User {$userId} - Defined exercise questions");

        // Get all completed exercise types for the user under the current assessment number
        $completedComponents = Submission::where('user_id', $userId)
            ->where('assessment_no', $latestAssessmentNo)
            ->pluck('exercise_type')
            ->toArray();
        Log::info("User {$userId} - Retrieved completed components for assessment_no {$latestAssessmentNo}: " . implode(', ', $completedComponents));

        // Filter out the exercise types that have already been completed
        $remainingExerciseTypes = array_diff(array_keys($exerciseData), $completedComponents);
        Log::info("User {$userId} - Remaining exercise types for assessment_no {$latestAssessmentNo}: " . implode(', ', $remainingExerciseTypes));

        $response = [
            'remainingExerciseTypes' => $remainingExerciseTypes,
            'exerciseData' => $exerciseData,
            'latestAssessmentNo' => $latestAssessmentNo
        ];
        Log::info("User {$userId} - Returning response: " . json_encode($response));

        return response()->json($response);
    }


    /**
     * Handle when the assessment is completed.
     *
     * @return \Illuminate\View\View
     */
    public function completed()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Increment the assessment_count column
        $user->assessment_count += 1;

        // Save the updated user data back to the database
        $user->save();

        // Log the update
        Log::info("User {$user->id}'s assessment_count incremented to {$user->assessment_count}.");

        // Generate feedback based on the user's scores
        $improvementFeedback = $this->provideImprovementFeedback($user);

        // Log the feedback generated
        Log::info("Improvement feedback generated for user {$user->id}: {$improvementFeedback}");

    }



    protected function provideImprovementFeedback($user)
    {
        $descriptiveScore = $user->descriptive_score;
        $dialogueScore = $user->dialogue_score;
        $characterScore = $user->character_score;

        // Calculate the average score
        $averageScore = ($descriptiveScore + $dialogueScore + $characterScore) / 3;

        // Initialize feedback array
        $feedback = [];

        // Provide feedback based on each score
        if ($descriptiveScore < $averageScore) {
            $feedback[] = "Your descriptive writing could use some enhancement. Focus on using vivid sensory details and creating a strong atmosphere.Please revisit the Descriotive Style Development section of the course";
        } else {
            $feedback[] = "Your descriptive writing is strong! Keep focusing on engaging the senses and painting a vivid picture in the reader's mind.";
        }

        if ($dialogueScore < $averageScore) {
            $feedback[] = "Improving your dialogue writing can make your characters more believable and your story more engaging. Please revisit the Dialogue Development section of the course";
        } else {
            $feedback[] = "Your dialogue writing is well-developed! Keep making your characters' voices distinct and purposeful.";
        }

        if ($characterScore < $averageScore) {
            $feedback[] = "Your character development might need more depth. Focus on understanding your characters' motivations, backgrounds, and personalities.Please revisit the Character Development section of the course";
        } else {
            $feedback[] = "Your characters are well-developed and compelling. Continue exploring their motivations and how these influence their decisions. ";
        }

        // Aggregate feedback into a final message
        $finalFeedback = implode(" ", $feedback);

        return $finalFeedback;
    }


        public function submitForFeedback(Request $request)
    {
        $userId = Auth::id();
        $latestAssessmentNo = Auth::user()->assessment_no ?? 1;

        // Now grade all the submissions for this assessment
        if ($this->checkAndGradeSubmissions($userId, $latestAssessmentNo)) {
            return redirect()->route('assessment.show')->with('success', 'All components graded successfully!');
        }

        return redirect()->route('assessment.show')->with('error', 'Grading failed or not all components were completed.');
    }


}
