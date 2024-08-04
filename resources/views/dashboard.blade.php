<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <head>
        <script src="//unpkg.com/alpinejs" defer></script>
    </head>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Question
                </h2>

                <div x-data="countdownTimer(20, 10)" class="p-6 bg-white border-b border-gray-200">
                    <!-- First Textarea and Radio Buttons -->
                    <div class="flex flex-col md:flex-row mb-16 gap-4 md:gap-8">
                        <textarea id="textarea1" class="flex-grow border rounded w-full md:w-3/4" rows="5"></textarea>
                        <div class="flex flex-col justify-around w-full md:w-1/4">
                            <label><input type="radio" name="exercise_type" value="Descriptive" checked> Description</label>
                            <label><input type="radio" name="exercise_type" value="Dialogue"> Dialogue</label>
                            <label><input type="radio" name="exercise_type" value="Plot"> Plot/Structure</label>
                            <label><input type="radio" name="exercise_type" value="Character"> Character</label>
                            <label><input type="radio" name="exercise_type" value="PoV"> Point of View</label>
                            <label><input type="radio" name="exercise_type" value="Style"> Style</label>
                        </div>
                    </div>

                    <!-- Space between textareas -->
                    <div class="h-12"></div>

                    <!-- Second Textarea with Countdown Timers and Buttons -->
                    <div class="flex flex-col mb-4">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            Your Writing
                        </h2>

                        <div class="flex flex-col md:flex-row justify-between mb-4 gap-4">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-800">Time Left</h3>
                                <div x-text="formatTime(timeLeft)" class="text-xl text-gray-800"></div>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg text-gray-800">Deletion Countdown</h3>
                                <div x-text="formatTime(deletionCountdown)" class="text-xl text-gray-800"></div>
                            </div>
                        </div>

                        <textarea id="textarea2" x-on:input="handleInput" class="border rounded mb-4 w-full" rows="10"></textarea>

                        <!-- Buttons at the bottom of the second textarea -->
                        <div class="flex flex-col md:flex-row gap-4">
                            <button id="show-question-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Show Question</button>
                            <button x-show="timeLeft === 0 && textarea2HasText" id="get-feedback-btn" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Get Feedback</button>
                        </div>
                    </div>

                    <!-- Third Textarea for Feedback and Submit Button -->
                    <div id="feedback-section" class="hidden flex flex-col mb-4">
                        <textarea id="textarea3" class="border rounded mb-4 w-full" rows="10"></textarea>
                         <form method="POST" action="{{ route('submit.text') }}" id="submission-form">
                            @csrf
                            <input type="hidden" name="textarea1" id="form-textarea1">
                            <input type="hidden" name="textarea2" id="form-textarea2">
                            <input type="hidden" name="textarea3" id="form-textarea3">
                            <input type="hidden" name="exercise_type" id="form-exercise-type">
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdownTimer', (initialTimeLeft, initialDeletionCountdown) => ({
                timeLeft: initialTimeLeft,
                deletionCountdown: initialDeletionCountdown,
                deletionTimer: null,
                countdownTimer: null,
                isTyping: false,
                lastTypedTime: null,
                textarea2HasText: false,

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
                            clearInterval(this.deletionTimer); // Stop deletion timer when countdown reaches 0
                        }
                    }, 1000);
                },

                startDeletionCountdown() {
                    if (this.deletionTimer) clearInterval(this.deletionTimer);

                    this.deletionTimer = setInterval(() => {
                        if (this.deletionCountdown > 0 && this.timeLeft > 0) { // Ensure deletionCountdown only runs when timeLeft > 0
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
                    this.textarea2HasText = document.getElementById('textarea2').value.length > 0;
                    this.lastTypedTime = new Date().getTime();
                },

                clearTextarea() {
                    if (this.timeLeft > 0) {
                        document.querySelector('textarea.border.rounded.mb-4').value = '';
                    }
                },

                init() {
                    setInterval(() => {
                        if (this.isTyping && new Date().getTime() - this.lastTypedTime > 3000) { // 3 seconds of inactivity
                            this.isTyping = false;
                            if (this.timeLeft > 0) { // Ensure deletionCountdown only starts when timeLeft > 0
                                this.startDeletionCountdown();
                            }
                        }
                    }, 1000);
                },
            }));
        });

        document.addEventListener("DOMContentLoaded", function() {
    // Show Question Button Logic
    document.getElementById('show-question-btn').addEventListener('click', function() {
        let selectedComponentType = document.querySelector('input[name="exercise_type"]:checked').value;

        axios.post('/generate-question', {
            component_type: selectedComponentType
        })
        .then(function (response) {
            document.getElementById('textarea1').value = response.data.question;
        })
        .catch(function (error) {
            console.error('Error generating question:', error);
        });
    });

    // Get Feedback Button Logic
    document.getElementById('get-feedback-btn').addEventListener('click', function() {
        let text1 = document.getElementById('textarea1').value;
        let text2 = document.getElementById('textarea2').value;

        if (text1 && text2) {
            let prompt3 = `Assuming that you are an expert in how Ernest Hemingway writes, can you provide feedback on the text in Textarea box 2 as an answer to the question posed in Textarea box 1.`;

            axios.post('/generate-feedback', {
                prompt: prompt3,
                text1: text1,
                text2: text2
            })
            .then(function (response) {
                document.getElementById('textarea3').value = response.data.feedback;
                document.getElementById('feedback-section').classList.remove('hidden');
            })
            .catch(function (error) {
                console.error('Error generating feedback:', error);
                alert('Error generating feedback. Please check the server logs for more details.');
            });
        } else {
            alert('Both textareas must be filled and the timer must reach 0 to get feedback.');
        }
    });

    // Form Submission Logic
    document.getElementById('submission-form').addEventListener('submit', function(e) {
        // Get the values of the textareas
        document.getElementById('form-textarea1').value = document.getElementById('textarea1').value;
        document.getElementById('form-textarea2').value = document.getElementById('textarea2').value;
        document.getElementById('form-textarea3').value = document.getElementById('textarea3').value;

        // Get the selected exercise type
        let selectedExerciseType = document.querySelector('input[name="exercise_type"]:checked').value;
        document.getElementById('form-exercise-type').value = selectedExerciseType;
    });
});

    </script>
</x-app-layout>
