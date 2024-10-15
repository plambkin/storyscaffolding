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
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">

                <!-- Scenario Description -->
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-xl font-semibold text-gray-800">Scenario</h3>
                    <p class="mt-4 text-gray-600 leading-relaxed">
                        Imagine you are writing a short story about a small, isolated village nestled in the mountains. The village is known for its peculiar traditions and the mysterious fog that rolls in every night, covering the entire area.
                    </p>
                </div>

                <!-- Exercise Form -->
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <form id="exercise-form" method="POST" action="{{ route('assessment.submit') }}">
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

                        <!-- Time and Deletion Countdown -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-700">Time Left</h3>
                                <p x-text="formatTime(timeLeft)" class="text-2xl text-gray-800 font-bold"></p>
                            </div>
                            <div class="text-right">
                                <h3 class="font-semibold text-lg text-gray-700">Deletion Countdown</h3>
                                <p x-text="formatTime(deletionCountdown)" class="text-2xl text-gray-800 font-bold"></p>
                            </div>
                        </div>

                        <!-- Answer Textarea -->
                        <div class="mb-6">
                            <textarea id="user_answer" name="textarea2"
                                      class="border-2 border-gray-300 rounded-lg w-full p-4 shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      rows="6" x-on:input="handleInput"></textarea>
                        </div>

                        <!-- Submit for Feedback Button -->
                        <div class="flex justify-end">
                            <button type="button" id="submit-feedback"
                                    @click="submitForFeedback"
                                    :disabled="isSubmitting"
                                    class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-700 hover:to-indigo-800 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out">
                                <span x-show="!isSubmitting">Submit for Feedback</span>
                                <span x-show="isSubmitting" class="flex items-center">
                                    Grading
                                    <span class="spinner-border ml-2 animate-spin"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
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
                timeLeft: 1800, // Adjust based on your needs
                deletionCountdown: 30,
                deletionTimer: null,
                countdownTimer: null,
                isTyping: false,
                lastTypedTime: null,
                textareaHasText: false,
                isSubmitting: false, // Tracks if the form is being submitted

                formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const remainingSeconds = seconds % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
                },

                startCountdown() {
                    if (this.countdownTimer) clearInterval(this.countdownTimer);
                    this.countdownTimer = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                        } else {
                            clearInterval(this.countdownTimer);
                            clearInterval(this.deletionTimer);
                        }
                    }, 1000);
                },

                startDeletionCountdown() {
                    if (this.deletionTimer) clearInterval(this.deletionTimer);
                    this.deletionTimer = setInterval(() => {
                        if (this.deletionCountdown > 0 && this.timeLeft > 0) {
                            this.deletionCountdown--;
                        } else if (this.deletionCountdown <= 0 && this.timeLeft > 0) {
                            this.clearTextarea();
                        }
                    }, 1000);
                },

                handleInput() {
                    if (!this.isTyping) {
                        this.isTyping = true;
                        this.startCountdown();
                        clearInterval(this.deletionTimer);
                    }
                    this.textareaHasText = document.getElementById('user_answer').value.length > 0;
                    this.lastTypedTime = new Date().getTime();
                },

                clearTextarea() {
                    if (this.timeLeft > 0) {
                        document.querySelector('#user_answer').value = '';
                    }
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

                init() {
                    this.fetchRemainingExercises();

                    setInterval(() => {
                        if (this.isTyping && new Date().getTime() - this.lastTypedTime > 3000) {
                            this.isTyping = false;
                            if (this.timeLeft > 0) {
                                this.startDeletionCountdown();
                            }
                        }
                    }, 1000);
                },

                // Submit for Feedback Function
                submitForFeedback() {
                    // Disable the button and show the spinner
                    this.isSubmitting = true;

                    // Submit the form
                    const form = document.getElementById('exercise-form');
                    form.submit();
                }
            };
        }
    </script>
</x-app-layout>
