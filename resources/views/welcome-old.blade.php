<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Story Scaffolding</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .hero-bg {
            background-image: url('https://example.com/your-background-image.jpg');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <!-- Hero Section -->
    <div class="hero-bg relative flex items-center justify-center min-h-screen text-center text-white">
        <div class="bg-black bg-opacity-50 p-12 rounded-lg">
            <h1 class="text-4xl font-extrabold">Welcome to Story Scaffolding</h1>
            <p class="mt-4 text-lg">Your ultimate writer's gym to build and refine your storytelling superpowers.</p>
            @if (Route::has('login'))
            <div class="mt-8">
                @auth
                <a href="{{ url('/dashboard') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Log in</a>
                <a href="{{ route('register') }}" class="ml-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Register</a>
                @endauth
            </div>
            @endif
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">About Story Scaffolding</h2>
                    <p class="mt-4 text-gray-600 dark:text-gray-400">
                        Story scaffolding is a gym for writers, breaking down all elements of writing, from novels to screenplays. Incorporating the latest neuroscience research, we help you become the best writer you can be.
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Benefits of Writing</h2>
                    <ul class="mt-4 text-gray-600 dark:text-gray-400 list-disc pl-6">
                        <li>Influence and Persuasion</li>
                        <li>Empathy and Connection</li>
                        <li>Legacy and Immortality</li>
                        <li>Problem-Solving and Critical Thinking</li>
                        <li>Empowerment and Voice</li>
                        <li>Healing and Transformation</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Detailed Section with Call to Action -->
        <div class="mt-12 bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Explore Our Writer's Gym</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">
                    Whether you're a hobbyist or a professional, our gym offers exercises to improve your writing in description, dialogue, character development, and more. With our premium features, you can receive personalized feedback from writing professionals.
                </p>
                <div class="mt-8 text-center">
                    <a href="{{ route('register') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg">Start Your Free Trial</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="bg-gray-900 text-gray-400 py-6">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <p class="text-sm">&copy; {{ date('Y') }} Story Scaffolding. All rights reserved.</p>
            </div>
            <div class="flex items-center">
                <a href="https://laravel.bigcartel.com" class="text-sm hover:underline">Shop</a>
                <a href="https://github.com/sponsors/taylorotwell" class="ml-4 text-sm hover:underline">Sponsor</a>
            </div>
        </div>
    </footer>
</body>
</html>
