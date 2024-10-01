<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import logging facade
use App\Models\Submission;
use GuzzleHttp\Client;

class RecommendationController extends Controller
{
    /**
     * Get assessment grades and generate improvement recommendations
     *
     * @param int $assessmentNo
     * @return \Illuminate\Http\JsonResponse
     */
    public function recommendCourses($assessmentNo)
    {
        $userId = Auth::id();
        Log::info("Recommendation process started for user: {$userId} and assessment number: {$assessmentNo}");

        // Step 1: Retrieve grades for the assessment
        $grades = $this->getAssessmentResults($userId, $assessmentNo);
        Log::info("Grades retrieved for user {$userId} and assessment {$assessmentNo}: ", $grades->toArray());

        // Step 2: Analyze grades and determine which areas need improvement
        $areasToImprove = $this->analyzeGrades($grades);
        Log::info("Areas identified for improvement for user {$userId}: " . implode(', ', $areasToImprove));

        // Step 3: Get relevant TalentLMS courses
        $recommendations = $this->getRecommendationsForImprovement($areasToImprove);
        Log::info("Recommendations generated for user {$userId}: ", $recommendations);

        // Step 4: Return recommendations as JSON or View
        return response()->json([
            'success' => true,
            'recommendations' => $recommendations
        ]);
    }

    /**
     * Retrieve all grades for the current assessment
     *
     * @param int $userId
     * @param int $assessmentNo
     * @return \Illuminate\Support\Collection
     */
    protected function getAssessmentResults($userId, $assessmentNo)
    {
        Log::info("Fetching assessment results for user {$userId}, assessment {$assessmentNo}");

        $grades = Submission::where('user_id', $userId)
            ->where('assessment_no', $assessmentNo)
            ->get(['exercise_type', 'grade']);

        if ($grades->isEmpty()) {
            Log::warning("No grades found for user {$userId}, assessment {$assessmentNo}");
        }

        return $grades;
    }

    /**
     * Analyze the grades to determine areas of improvement
     *
     * @param \Illuminate\Support\Collection $grades
     * @param float $threshold
     * @return array
     */
    protected function analyzeGrades($grades, $threshold = 7.0)
    {
        Log::info("Analyzing grades with threshold {$threshold}");

        $areasToImprove = [];

        foreach ($grades as $grade) {
            Log::info("Processing grade for {$grade->exercise_type}: {$grade->grade}");

            if ($grade->grade < $threshold) {
                Log::info("Grade for {$grade->exercise_type} is below threshold. Marking for improvement.");
                $areasToImprove[] = $grade->exercise_type;
            }
        }

        if (empty($areasToImprove)) {
            Log::info("No areas found that need improvement based on the grades.");
        }

        return $areasToImprove;
    }

    /**
     * Map areas to TalentLMS courses and return recommendations
     *
     * @param array $areasToImprove
     * @return array
     */
    protected function getRecommendationsForImprovement($areasToImprove)
    {
        Log::info("Generating recommendations for areas to improve: " . implode(', ', $areasToImprove));

        $recommendations = [];

        foreach ($areasToImprove as $area) {
            Log::info("Fetching TalentLMS course for area: {$area}");
            
            $courseId = $this->getTalentLmsCourseId($area);

            if ($courseId) {
                Log::info("Found course ID {$courseId} for area {$area}. Fetching course details.");
                
                $courseDetails = $this->getTalentLmsCourse($courseId);
                
                Log::info("Course details retrieved for course ID {$courseId}: ", $courseDetails);

                $recommendations[] = [
                    'area' => $area,
                    'course_name' => $courseDetails['name'],
                    'course_url' => $courseDetails['url'],
                ];
            } else {
                Log::warning("No course found for area: {$area}");
            }
        }

        if (empty($recommendations)) {
            Log::warning("No recommendations could be generated.");
        }

        return $recommendations;
    }

    /**
     * Map exercise types to TalentLMS course IDs
     *
     * @param string $exerciseType
     * @return string|null
     */
    protected function getTalentLmsCourseId($exerciseType)
    {
        Log::info("Mapping exercise type {$exerciseType} to TalentLMS course ID.");

        $courseMapping = [
            'Descriptive' => '126',
            'Dialogue' => '126',
            'Plot/Structure' => 'plot_course_id',
            'POV' => 'pov_course_id',
            'Style' => 'style_course_id',
            'Character' => 'character_course_id',
        ];

        $courseId = $courseMapping[$exerciseType] ?? null;

        if ($courseId) {
            Log::info("Mapped exercise type {$exerciseType} to course ID {$courseId}.");
        } else {
            Log::warning("No course mapping found for exercise type {$exerciseType}.");
        }

        return $courseId;
    }

    /**
     * Fetch TalentLMS course details using the TalentLMS API
     *
     * @param string $courseId
     * @return array
     */
         protected function getTalentLmsCourse($courseId)
    {
        Log::info("Starting TalentLMS course details fetch for course ID: {$courseId}.");

        try {
            // Step 1: Log retrieval of API Key and Domain
            $talentLmsApiKey = env('TALENTLMS_API_KEY');
            $domain = env('TALENTLMS_DOMAIN'); // Example: yourorganization.talentlms.com

            Log::info("Using TalentLMS Domain: {$domain}");
            Log::info("Using TalentLMS API Key (partial): " . substr($talentLmsApiKey, 0, 5) . '****');  // Only show a portion of the key for security reasons.

            // Step 2: Log the URL being requested
            //$url = "https://{$domain}/api/v1/courses/{$courseId}";
             //$url = "https://{$domain}/api/v1/courses";

            $url = "https://{$domain}/api/v1/courses/126";

            Log::info("Sending GET request to TalentLMS API: {$url}");

            // Step 3: Initialize Guzzle Client and Send the Request
            $client = new Client();
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => "Basic " . base64_encode("{$talentLmsApiKey}:"),
                ]
            ]);

            // Step 4: Log the raw response status and headers
            Log::info("TalentLMS API Response Status: " . $response->getStatusCode());
            Log::info("TalentLMS API Response Headers: " . json_encode($response->getHeaders()));

            // Step 5: Parse the JSON Response
            $courseDetails = json_decode($response->getBody(), true);

            // Log the full course details response to analyze the structure
            Log::info("TalentLMS course response received: ", $courseDetails);

            // Step 6: Validate if the expected fields 'name' and 'url' are present
            if (isset($courseDetails['name']) && isset($courseDetails['url'])) {
                Log::info("Course name and URL found for course ID {$courseId}: Name - {$courseDetails['name']}, URL - {$courseDetails['url']}");
            } else {
                Log::warning("Expected fields 'name' or 'url' missing in the TalentLMS course response for course ID {$courseId}");
            }

            // Return the course details if the structure is correct
            return $courseDetails;

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Step 7: Log any Client exceptions (4xx errors)
            Log::error("ClientException while fetching TalentLMS course details for course ID {$courseId}: " . $e->getResponse()->getStatusCode() . ' - ' . $e->getMessage());
            return [];
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Step 8: Log any Server exceptions (5xx errors)
            Log::error("ServerException while fetching TalentLMS course details for course ID {$courseId}: " . $e->getResponse()->getStatusCode() . ' - ' . $e->getMessage());
            return [];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Step 9: Log any other network-related exceptions
            Log::error("RequestException while fetching TalentLMS course details for course ID {$courseId}: " . $e->getMessage());
            return [];
        } catch (\Exception $e) {
            // Step 10: Log any general exceptions
            Log::error("Unexpected error while fetching TalentLMS course details for course ID {$courseId}: " . $e->getMessage());
            return [];
        }
    }

    

}
