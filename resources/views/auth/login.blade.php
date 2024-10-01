<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - Story Scaffolding</title>

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

        .login-container {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            font-size: 24px;
            font-weight: 700;
            color: #374151;
            text-align: center;
            margin-bottom: 20px;
        }

        .login-button {
            background-color: #3b82f6;
            border-radius: 5px;
            padding: 10px 20px;
            color: #ffffff;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #2563eb;
        }

        .link {
            color: #3b82f6;
        }

        .link:hover {
            color: #2563eb;
            text-decoration: underline;
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md mx-auto p-6 login-container">
            <div class="text-center">
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-700 mx-auto" />
                </a>
            </div>
            <div class="login-header mt-4">Welcome Back</div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-label for="email" :value="__('Email')" class="block text-gray-700" />
                    <x-input id="email" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 pl-2" type="email" name="email" :value="old('email')" required autofocus />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-label for="password" :value="__('Password')" class="block text-gray-700" />
                    <x-input id="password" class="block mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 pl-2"  type="password" name="password" required autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                        <span class="ml-2 text-gray-600 text-sm">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="link text-sm" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" class="login-button w-full">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                @if (Route::has('register'))
                    <p class="text-sm text-gray-600">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('register') }}" class="link">
                            {{ __('Register') }}
                        </a>
                    </p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
