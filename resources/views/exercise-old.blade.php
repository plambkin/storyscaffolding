<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Assessment Exercise') }}
        </h2>
    </x-slot>

    <div class="py-12"
        x-data="exerciseApp()"
        x-init="init()">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Scenario Description -->
            <div class="mb-6 bg-white p-6 rounded-lg shadow-lg border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-800">Scenario</h3>
                <p class="mt-4 text-gray-600 leading-relaxed">
                    Imagine you are writing a short story about a small, isolated village nestled in the mountains. The village is known for its peculiar traditions and the mysterious fog that rolls in every night, covering the entire area.
                </p>
            </div>

            <!-- Exercise Form -->
            <form id="exercise-form" method="POST" action="{{ route('exercise.submit') }}">
                @csrf

                <!-- Exercise Type Selection -->
                <div class="mb-6">
                    <label for="exercise_type" class="block text-sm font-medium text-gray-700">Choose Exercise Type:</label>
                    <select id="exercise_type" name="exercise_type"
                            x-model="exerciseType"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            x-on:change="updateQuestion">
                        <!-- Default placeholder option -->
                        <option value="" disabled selected>Choose Exercise Type</option>

                        <!-- Dynamic options -->
                        <template x-for="type in remainingExerciseTypes" :key="type">
                            <option :value="type" x-text="type"></option>
                        </template>
                    </select>
                </div>

                <!-- Exercise Question -->
                <div class="mb-4">
                    <label for="exercise_question" class="block text-lg font-semibold text-gray-700">
                        <span x-text="exerciseType ? exerciseType + ' Question' : 'Question will appear here'"></span>
                    </label>
                    <p class="mt-2 text-gray-600 leading-relaxed" id="question_text" x-text="currentQuestion"></p>
                    <input type="hidden" name="textarea1" :value="currentQuestion">
                </div>

                <!-- Answer Textarea -->
                <div class="mb-6">
                    <textarea id="user_answer" name="textarea2"
                              class="border-2 border-gray-300 rounded-lg w-full p-4 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                              rows="6" x-on:input="handleInput"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out">
                        Submit for Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function exerciseApp() {
            return {
                exerciseType: '',
                currentQuestion: '',
                remainingExerciseTypes: [],
                exerciseData: {},
                latestAssessmentNo: null,

                init() {
                    this.fetchRemainingExercises();
                },

                fetchRemainingExercises() {
                    fetch('/api/remaining-exercises')
                        .then(response => response.json())
                        .then(data => {
                            this.remainingExerciseTypes = data.remainingExerciseTypes;
                            this.exerciseData = data.exerciseData;
                            this.latestAssessmentNo = data.latestAssessmentNo;

                            // Ensure "Choose Exercise Type" is always the first option
                            this.exerciseType = ''; // Reset to force "Choose Exercise Type" to show
                        });
                },

                updateQuestion() {
                    this.currentQuestion = this.exerciseData[this.exerciseType] || '';
                },

                submitForm() {
                    const form = document.getElementById('exercise-form');
                    const formData = new FormData(form);

                    fetch('/api/submit-exercise', {
                        method: 'POST',
                        body: formData,
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Refresh the exercise types and load the next one
                                this.fetchRemainingExercises();
                                document.getElementById('user_answer').value = '';
                            } else {
                                alert(data.message || 'An error occurred.');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            };
        }
    </script>
</x-app-layout>
