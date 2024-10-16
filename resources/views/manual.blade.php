<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Story-Gym User Manual') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-4">Overview</h1>
                    <p class="mb-6">
                        Story-Gym.com is a platform designed to enhance storytelling skills through structured exercises and personalized feedback. Upon registering, users agree to an Accountability Contract to stay motivated throughout their journey.
                    </p>

                    <h2 class="text-xl font-bold mt-6 mb-2">Getting Started</h2>
                    
                    <h3 class="text-lg font-semibold mt-4">Accountability Contract:</h3>
                    <p class="mb-6">
                        After registration, users are directed to the Accountability Contract page, which they must agree to before proceeding. The contract encourages daily commitment to the exercises and outlines the benefits of consistent practice.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">Assessment Section:</h3>
                    <p class="mb-6">
                        After agreeing to the contract, users are taken to a brief manual (5-minute read) and the Assessment Section. This initial assessment measures skills in three areas: Descriptive Writing, Dialogue, and Character Development. The user selects an exercise type from a dropdown menu, generates a question, and has 30 minutes to complete it. While answering, if typing pauses for more than 3 seconds, a Deletion Counter starts. If it reaches zero, the answer is erased, encouraging focus and avoiding procrastination.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">Submitting Answers:</h3>
                    <p class="mb-6">
                        Once the user completes an answer, they press the Submit button, which stores their response in the database along with the question type and submission date.
                    </p>

                    <h2 class="text-xl font-bold mt-6 mb-2">Grading and Learning Path</h2>
                    <p class="mb-6">
                        After submitting all three exercises, the platform automatically grades them and determines the user’s current level. Based on the grades, the user is directed to a storytelling course on an external Learning Management System (LMS) for further improvement.
                    </p>

                    <h2 class="text-xl font-bold mt-6 mb-2">Explanation of Menu Items</h2>
                    
                    <h3 class="text-lg font-semibold mt-4">Dashboard:</h3>
                    <p class="mb-6">
                        Allows users to practice various exercise types (e.g., Description, Dialogue, Character). Users select an exercise, generate a question, and have a set time to respond before submitting their answer.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">Profile:</h3>
                    <p class="mb-6">
                        Stores user details like name, email, and gravatar image.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">Submissions:</h3>
                    <p class="mb-6">
                        Displays a history of all user submissions. Users can download their work as a PDF for later review.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">The Assessment:</h3>
                    <p class="mb-6">
                        This section contains the required assessment for new users to determine their initial skill level. It focuses on Description, Dialogue, and Character Development.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">The Course:</h3>
                    <p class="mb-6">
                        Provides a link to a comprehensive storytelling course on the LMS, using the same login credentials as Story-Gym.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">The Learning Path (Coming Soon):</h3>
                    <p class="mb-6">
                        This feature will offer a guided path for further storytelling improvement.
                    </p>

                    <h3 class="text-lg font-semibold mt-4">The Leaderboard:</h3>
                    <p class="mb-6">
                        Displays top performers on the platform, ranked based on their skills in Description, Dialogue, and Character Development.
                    </p>

                    <h2 class="text-xl font-bold mt-6 mb-2">Conclusion</h2>
                    <p class="mb-6">
                        Story-Gym.com is a practical and engaging platform for improving storytelling skills. With focused exercises, real-time feedback, and structured learning paths, users can build their narrative abilities and track their progress. The platform's emphasis on accountability and consistency ensures that users stay motivated on their storytelling journey.
                    </p>

                    <!-- Back Button -->
                    <div class="mt-6">
                        <a href="javascript:history.back()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
