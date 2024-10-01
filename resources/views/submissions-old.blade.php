<x-app-layout>
    <!-- Page Heading -->
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            Submissions for {{ $user->name }}
        </h2>
    </x-slot>

    <!-- Page Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Submission Items -->
        <div class="space-y-6">
            @foreach ($submissions->sortByDesc('created_at') as $submission)
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        Exercise Type: {{ $submission->exercise_type }}
                    </h3>

                    <!-- Label and Display for Date Created -->
                    <div class="mb-6">
                        <label for="date-created-{{ $submission->id }}" class="block text-lg font-medium text-gray-900">Date Created</label>
                        <p id="date-created-{{ $submission->id }}" class="text-gray-700">{{ $submission->created_at->format('F d, Y') }}</p>
                    </div>
                    
                    <!-- Label and Textarea for Question -->
                    <div class="mb-6">
                        <label for="textarea1-{{ $submission->id }}" class="block text-lg font-medium text-gray-900">Question</label>
                        <textarea id="textarea1-{{ $submission->id }}" name="textarea1[]" class="w-full mt-2 p-3 border-gray-300 rounded-lg shadow-lg focus:ring focus:ring-blue-500 focus:border-blue-500">{{ $submission->textarea1 }}</textarea>
                    </div>

                    <!-- Label and Textarea for Answer -->
                    <div class="mb-6">
                        <label for="textarea2-{{ $submission->id }}" class="block text-lg font-medium text-gray-900">Answer</label>
                        <textarea id="textarea2-{{ $submission->id }}" name="textarea2[]" class="w-full mt-2 p-3 border-gray-300 rounded-lg shadow-lg focus:ring focus:ring-blue-500 focus:border-blue-500">{{ $submission->textarea2 }}</textarea>
                    </div>

                    <!-- Label and Textarea for Feedback with Auto-resize -->
                    <div class="mb-6">
                        <label for="textarea3-{{ $submission->id }}" class="block text-lg font-medium text-gray-900">Feedback</label>
                        <textarea id="textarea3-{{ $submission->id }}" name="textarea3[]" class="w-full mt-2 p-3 border-gray-300 rounded-lg shadow-lg focus:ring focus:ring-blue-500 focus:border-blue-500 overflow-hidden resize-none" oninput="autoResize(this)">{{ $submission->textarea3 }}</textarea>
                    </div>

                    <!-- Label and Display for Grade -->
                    <div class="mb-6">
                        <label for="grade-{{ $submission->id }}" class="block text-lg font-medium text-gray-900">Grade</label>
                        <p id="grade-{{ $submission->id }}" class="text-gray-700">{{ $submission->grade }}</p>
                    </div>

                    <input type="hidden" name="exercise_type[]" value="{{ $submission->exercise_type }}">
                </div>
            @endforeach
        </div>

        <!-- Export to PDF Button -->
        <div class="mt-8 text-center">
            <form id="exportForm" method="POST" action="{{ route('export.pdf') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <button type="submit" class="bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 text-white font-bold py-3 px-6 rounded-full shadow-md transition duration-300 ease-in-out">
                    Export to PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Auto-resize Textarea Script -->
    <script>
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        // Automatically resize textareas on page load
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('textarea').forEach(function (textarea) {
                autoResize(textarea);
            });
        });
    </script>
</x-app-layout>
