<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accountability Contract') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="font-semibold text-2xl text-gray-800 leading-tight mt-6">Accountability Contract</h1><br></br>
                    <p>Please read and agree to the Accountability Contract before proceeding.</p>

                    <h2 class="font-semibold text-lg text-gray-800 leading-tight mt-6">The Promise:</h2>

                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-700 leading-tight">1. Influence and Persuasion</h3>
                        <p>By practicing daily, you will learn to weave stories that can persuade and influence, whether in personal conversations, business presentations, or written work. Your ability to sway opinions and move people to action will be significantly sharpened.</p>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-700 leading-tight">2. Empathy and Connection</h3>
                        <p>Through crafting stories, you will step into the shoes of your characters, enhancing your empathy. This will not only make your stories more authentic but also deepen your connections with your audience, as you express shared experiences and emotions.</p>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-700 leading-tight">3. Legacy and Immortality</h3>
                        <p>By committing to storytelling, you are creating a legacy. Stories have the power to live on far beyond your time. This daily practice is your step toward immortality through the written or spoken word.</p>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-700 leading-tight">4. Problem-Solving and Critical Thinking</h3>
                        <p>Storytelling involves navigating complex narratives, resolving conflicts, and understanding different perspectives. Over the next 4 weeks, you will strengthen your problem-solving and critical thinking skills, enabling you to craft intricate plots and character arcs with ease.</p>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-700 leading-tight">5. Empowerment of Voice</h3>
                        <p>Your voice is your unique perspective, and storytelling will empower it. In these 10 minutes a day, you will uncover and hone your distinct narrative style, gaining confidence in expressing yourself through words.</p>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-700 leading-tight">6. Healing and Self-Reflection</h3>
                        <p>Storytelling offers a powerful way to reflect on personal experiences and emotions. As you create and tell stories, you will find it to be a healing process, providing closure and deeper understanding of your life journey.</p>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold text-md text-gray-700 leading-tight">7. Personal Transformation</h3>
                        <p>In the process of crafting your stories, you will grow. You will not just become a better storyteller but also a more thoughtful, self-aware, and expressive person. By the end of these 4 weeks, you will see a transformation in how you think, feel, and communicate.</p>
                    </div>

                    <h2 class="font-semibold text-lg text-gray-800 leading-tight mt-6">Your Daily Commitment:</h2>
                    <p><strong>Time Required:</strong> 10 minutes a day</p>
                    <p><strong>Duration:</strong> 4 weeks (28 days)</p>

                    <h2 class="font-semibold text-lg text-gray-800 leading-tight mt-6">The Outcome:</h2>
                    <p>By following this commitment, you will have greatly enhanced your storytelling abilities. You will be able to craft compelling, engaging stories that not only entertain but also influence, connect, and transform both you and your audience.</p>
                    <p>This journey is about growth—both as a storyteller and as an individual. Let your stories shape the world around you, just as they have shaped the legacies of the great authors who came before.</p>

                    <div class="mt-6">
                        <h3 class="font-semibold text-md text-gray-800 leading-tight">Signature:</h3>
                        <p>I, ___________________, commit to investing 10 minutes a day to enhance my storytelling abilities, knowing the incredible benefits that will come from this journey.</p>
                        <p>Date: ___________________</p>
                    </div>

                    <form method="POST" action="{{ route('accountability.contract.accept') }}">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                            I Agree to the Accountability Contract
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
