<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register - Story Scaffolding</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f3f4f6;
            background-image: linear-gradient(135deg, #dfe9f3 0%, #ffffff 100%);
        }

        .register-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .register-header {
            font-size: 24px;
            font-weight: 700;
            color: #374151;
            text-align: center;
            margin-bottom: 20px;
        }

        .register-button {
            background-color: #10b981;
            border-radius: 5px;
            padding: 10px 20px;
            color: #ffffff;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .register-button:hover {
            background-color: #059669;
        }

        .link {
            color: #10b981;
        }

        .link:hover {
            color: #059669;
            text-decoration: underline;
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md mx-auto p-6 register-container">
            <div class="text-center">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-700 mx-auto" />
                </a>
            </div>
            <div class="register-header mt-4">Create Your Account</div>

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-label for="name" :value="__('Name')" class="block text-gray-700" />
                    <x-input id="name" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 pl-2" type="text" name="name" :value="old('name')" required autofocus />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-label for="email" :value="__('Email')" class="block text-gray-700" />
                    <x-input id="email" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 pl-2" type="email" name="email" :value="old('email')" required />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-label for="password" :value="__('Password')" class="block text-gray-700" />
                    <x-input id="password" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 pl-2" type="password" name="password" required autocomplete="new-password" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-label for="password_confirmation" :value="__('Confirm Password')" class="block text-gray-700" />
                    <x-input id="password_confirmation" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 pl-2" type="password" name="password_confirmation" required />
                </div>

                <!-- Register Button -->
                <div class="flex items-center justify-between mt-6">
                    <a class="link text-sm" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="register-button">
                        {{ __('Register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
