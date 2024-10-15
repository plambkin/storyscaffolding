<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Story Gym') }}
        </h2>
    </x-slot>

    <head>
        <!-- Include Alpine.js and Tailwind CSS -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>

    <div class="py-12" 
        x-data="{
            exerciseType: 'Descriptive',
            isPremium: {{ Auth::user()->subscription_type === 'Premium' ? 'true' : 'false' }},
            showModal: false,
            timeLeft: 1800,
            deletionCountdown: 300,
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
                this.textarea2HasText = document.getElementById('textarea2').value.length > 0;
                this.lastTypedTime = new Date().getTime();
            },

            clearTextarea() {
                if (this.timeLeft > 0) {
                    document.querySelector('#textarea2').value = '';
                }
            },

            finishExercise() {
                const textarea2Value = document.getElementById('textarea2').value.trim();

                if (!textarea2Value) {
                    alert('Please supply an answer to obtain Feedback.');
                    return;
                }

                this.timeLeft = 0;
                this.deletionCountdown = 0;
                document.getElementById('get-feedback-btn').classList.remove('hidden');
                document.getElementById('im-finished-btn').classList.add('hidden');
            },

            init() {
                setInterval(() => {
                    if (this.isTyping && new Date().getTime() - this.lastTypedTime > 3000) { 
                        this.isTyping = false;
                        if (this.timeLeft > 0) {
                            this.startDeletionCountdown();
                        }
                    }
                }, 1000);
            }
        }"
        x-init="init()">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- Skill Levels Section -->
                <div class="p-6 bg-white border-b border-gray-200 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-700">Descriptive Score</h3>
                        <p class="text-3xl text-blue-600 font-bold">{{ $user->descriptive_score }}</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-700">Dialogue Score</h3>
                        <p class="text-3xl text-blue-600 font-bold">{{ $user->dialogue_score }}</p>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-700">Character Score</h3>
                        <p class="text-3xl text-blue-600 font-bold">{{ $user->character_score }}</p>
                    </div>
                </div>

                <!-- Question Section -->
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Question</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <textarea 
                            id="textarea1" 
                            x-bind:value="exerciseType === 'Custom' ? 'Please type your text in here' : ''" 
                            class="border-2 border-blue-500 rounded w-full p-4 shadow-sm md:col-span-3" 
                            rows="5"></textarea>
                        <div class="flex flex-col space-y-2">
                            <label><input type="radio" name="exercise_type" value="Descriptive" x-model="exerciseType" class="mr-2"> Description</label>
                            <label><input type="radio" name="exercise_type" value="Dialogue" x-model="exerciseType" class="mr-2"> Dialogue</label>
                            <label><input type="radio" name="exercise_type" value="Plot" x-model="exerciseType" class="mr-2"> Plot/Structure (Coming Soon)</label>
                            <label><input type="radio" name="exercise_type" value="Character" x-model="exerciseType" class="mr-2"> Character </label>
                            <label><input type="radio" name="exercise_type" value="PoV" x-model="exerciseType" class="mr-2"> Point of View (Coming Soon)</label>
                            <label><input type="radio" name="exercise_type" value="Style" x-model="exerciseType" class="mr-2"> Style (Coming Soon)</label>
                            <label>
                                <input type="radio" name="exercise_type" value="Custom" class="mr-2"
                                    @change="
                                        if (!isPremium) { 
                                            showModal = true; 
                                            exerciseType = 'Descriptive'; 
                                        } else {
                                            exerciseType = 'Custom';
                                        }
                                    "> Custom
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Writing Section -->
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Your Writing</h2>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <h3 class="font-semibold text-lg text-gray-700">Time Left (Secs)</h3>
                            <p x-text="formatTime(timeLeft)" class="text-2xl text-gray-800 font-bold"></p>
                        </div>
                        <div class="text-right">
                            <h3 class="font-semibold text-lg text-gray-700">Deletion Countdown (Secs)</h3>
                            <p x-text="formatTime(deletionCountdown)" class="text-2xl text-gray-800 font-bold"></p>
                        </div>
                    </div>
                    <textarea id="textarea2" x-on:input="handleInput" class="border rounded w-full p-4 mb-4" rows="10"></textarea>

                    <div class="flex space-x-4">
                        <button id="show-question-btn" x-show="exerciseType !== 'Custom'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded relative">
                            <span id="button-text">Show Question</span>
                            <svg id="spinner" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden absolute inset-y-0 right-4 my-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </button>
                        <button id="im-finished-btn" @click="finishExercise" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">I'm Finished</button>
                        <button id="get-feedback-btn" x-show="timeLeft === 0 && textarea2HasText" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded hidden">Get Feedback</button>
                    </div>
                </div>

                <!-- Feedback Section -->
                <div id="feedback-section" class="hidden p-6 bg-gray-50 border-b border-gray-200">
                    <textarea id="textarea3" class="border rounded w-full p-4 mb-4" rows="10"></textarea>
                    <div id="grade-display" class="text-2xl font-semibold text-gray-800 mb-4"></div>
                    <form method="POST" action="{{ route('submit.text') }}" id="submission-form">
                        @csrf
                        <input type="hidden" name="textarea1" id="form-textarea1">
                        <input type="hidden" name="textarea2" id="form-textarea2">
                        <input type="hidden" name="textarea3" id="form-textarea3">
                        <input type="hidden" name="exercise_type" id="form-exercise-type">
                        <input type="hidden" name="grade" id="form-grade">
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Dialog for Non-Premium Users -->
        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-75">
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Only for Premium Users</h2>
                <p class="text-gray-600 mb-4">This feature is only available for Premium users. Please upgrade to access it.</p>
                <button @click="showModal = false" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2">Close</button>
                <a href="{{ route('subscription.upgrade') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Upgrade</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const showQuestionBtn = document.getElementById('show-question-btn');
            const spinner = document.getElementById('spinner');
            const buttonText = document.getElementById('button-text');

            showQuestionBtn.addEventListener('click', function() {
                let selectedComponentType = document.querySelector('input[name="exercise_type"]:checked').value;

                // Show the spinner and change the button text
                buttonText.textContent = "Formulating Question";
                spinner.classList.remove('hidden');

                axios.post('/generate-question', {
                    component_type: selectedComponentType
                })
                .then(function (response) {
                    document.getElementById('textarea1').value = response.data.question;
                })
                .catch(function (error) {
                    console.error('Error generating question:', error);
                })
                .finally(function() {
                    // Hide the spinner and revert the button text
                    buttonText.textContent = "Show Question";
                    spinner.classList.add('hidden');
                });
            });

            document.getElementById('get-feedback-btn').addEventListener('click', function() {
                let text1 = document.getElementById('textarea1').value;
                let text2 = document.getElementById('textarea2').value;
                let feedbackButton = document.getElementById('get-feedback-btn');

                if (text1 && text2) {
                    feedbackButton.innerHTML = 'Generating Feedback...';
                    feedbackButton.disabled = true;
                    feedbackButton.classList.add('cursor-not-allowed', 'opacity-50');

                    axios.post('/generate-mark', {
                        text2: text2
                    })
                    .then(function (response) {
                        let grade = response.data.grade;
                        document.getElementById('grade-display').textContent = `Grade: ${grade}`;

                        axios.post('/generate-feedback', {
                            prompt: `Assuming that you are an expert in how Ernest Hemingway writes, can you provide feedback on the text in Textarea box 2 as an answer to the question posed in Textarea box 1.`,
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
                        })
                        .finally(function() {
                            feedbackButton.innerHTML = 'Get Feedback';
                            feedbackButton.disabled = false;
                            feedbackButton.classList.remove('cursor-not-allowed', 'opacity-50');
                        });
                    })
                    .catch(function (error) {
                        console.error('Error generating mark:', error);
                        alert('Error generating mark. Please check the server logs for more details.');
                        feedbackButton.innerHTML = 'Get Feedback';
                        feedbackButton.disabled = false;
                        feedbackButton.classList.remove('cursor-not-allowed', 'opacity-50');
                    });
                } else {
                    alert('Both textareas must be filled and the timer must reach 0 to get feedback.');
                }
            });

            document.getElementById('submission-form').addEventListener('submit', function(e) {
                document.getElementById('form-textarea1').value = document.getElementById('textarea1').value;
                document.getElementById('form-textarea2').value = document.getElementById('textarea2').value;
                document.getElementById('form-textarea3').value = document.getElementById('textarea3').value;

                let selectedExerciseType = document.querySelector('input[name="exercise_type"]:checked').value;
                document.getElementById('form-exercise-type').value = selectedExerciseType;

                let grade = document.getElementById('grade-display').textContent.replace('Grade: ', '').trim();
                document.getElementById('form-grade').value = grade;
            });
        });
    </script>
</x-app-layout>
