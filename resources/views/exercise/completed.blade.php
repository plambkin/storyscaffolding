<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment Completed') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4">Congratulations on completing your assessment!</h3>
                <p class="text-lg mb-6">Well done on completing all parts of your assessment. Here's how you performed:</p>

                <!-- Display the scores -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="text-center">
                        <h4 class="text-xl font-semibold text-gray-700">Descriptive Score</h4>
                        <p class="text-3xl text-blue-600 font-bold">{{ $user->descriptive_score }}</p>
                    </div>
                    <div class="text-center">
                        <h4 class="text-xl font-semibold text-gray-700">Dialogue Score</h4>
                        <p class="text-3xl text-blue-600 font-bold">{{ $user->dialogue_score }}</p>
                    </div>
                    <div class="text-center">
                        <h4 class="text-xl font-semibold text-gray-700">Character Score</h4>
                        <p class="text-3xl text-blue-600 font-bold">{{ $user->character_score }}</p>
                    </div>
                </div>

                <!-- Display the improvement feedback -->
                <div class="bg-gray-100 p-4 rounded-lg shadow-lg">
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Your Improvement Feedback</h4>
                    <p>{{ $improvementFeedback }}</p>
                </div>

                <!-- Next Steps -->
                <div class="mt-6">
                    <a href="{{ route('exercise.show') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Start New Assessment
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
