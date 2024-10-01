<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-900 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white shadow-xl rounded-lg overflow-hidden">
            @php
                $email = strtolower(trim($user->email));  // Ensure email is properly formatted
                $emailHash = md5($email);
                $gravatarUrl = "https://www.gravatar.com/avatar/$emailHash?s=64&d=identicon";
            @endphp
            <div class="flex items-center px-6 py-4 bg-gradient-to-r from-blue-500 to-green-500">
                <img class="h-16 w-16 rounded-full border-2 border-white shadow-sm mr-4" src="{{ $gravatarUrl }}" alt="{{ $user->name }}">
                <div>
                    <h3 class="text-2xl font-bold text-white">{{ $user->name }}</h3>
                    <p class="text-lg font-semibold text-gray-100">Member since {{ $user->created_at->format('F Y') }}</p>
                </div>
            </div>
            <div class="px-6 py-4">
                <!-- Profile Information Section -->
                <div class="mb-6">
                    <h4 class="text-xl font-semibold text-gray-800 mb-2">Profile Information</h4>
                    <div class="flex items-center mb-2">
                        <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.11 0 2-.89 2-2 0-.28-.08-.54-.21-.78.1-.18.21-.37.21-.62 0-.83-.68-1.5-1.5-1.5-.22 0-.42.04-.61.13-.37-.32-.84-.53-1.39-.53-.28 0-.54.08-.78.21-.18-.1-.37-.21-.62-.21-.83 0-1.5.68-1.5 1.5 0 .22.04.42.13.61-.32.37-.53.84-.53 1.39 0 .28.08.54.21.78-.1.18-.21.37-.21.62 0 .83.68 1.5 1.5 1.5.22 0 .42-.04.61-.13.37.32.84.53 1.39.53.28 0 .54-.08.78-.21.18.1.37.21.62.21.83 0 1.5-.68 1.5-1.5 0-.22-.04-.42-.13-.61.32-.37.53-.84.53-1.39 0-.28-.08-.54-.21-.78.1-.18.21-.37.21-.62zM9 11H7v-2H5v2H3v2h2v2h2v-2h2v-2z"></path>
                        </svg>
                        <p class="text-lg font-semibold text-gray-600">Email: {{ $user->email }}</p>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 3a1 1 0 00-1 1v3h14V4a1 1 0 00-1-1H6zM4 8v13a1 1 0 001 1h14a1 1 0 001-1V8H4zm5 4h6"></path>
                        </svg>
                        <p class="text-lg font-semibold text-gray-600">Subscription: {{ $user->subscription_type }}</p>
                    </div>
                </div>

                <!-- Upgrade Plan Button -->
                <div class="mt-6 text-center">
                    <button class="bg-gradient-to-r from-green-500 to-blue-500 hover:from-blue-500 hover:to-green-500 text-white font-bold py-2 px-6 rounded-full shadow-lg transition duration-300 ease-in-out">
                        Upgrade Plan
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
