<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-900 leading-tight">
            {{ __('Leaderboard') }}
        </h2>
    </x-slot>

    <head>
        <!-- Include Tailwind CSS for styling -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    </head>

    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="hidden md:block">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-blue-500 to-indigo-600">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wider">Rank</th>
                                    <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white uppercase tracking-wider">User</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-white uppercase tracking-wider">Descriptive</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-white uppercase tracking-wider">Dialogue</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-white uppercase tracking-wider">Character</th>
                                    <th scope="col" class="px-6 py-3 text-center text-sm font-semibold text-white uppercase tracking-wider">Overall</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $rank = 1; @endphp
                                @foreach ($users as $user)
                                    @php
                                        $email = strtolower(trim($user->email));
                                        $emailHash = md5($email);
                                        $gravatarUrl = "https://www.gravatar.com/avatar/$emailHash?s=40&d=identicon";
                                    @endphp
                                    <tr class="{{ $loop->index % 2 === 0 ? 'bg-gray-50' : 'bg-white' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                            <div class="text-lg font-semibold text-gray-700">
                                                {{ $rank }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img class="h-10 w-10 rounded-full" src="{{ $gravatarUrl }}" alt="{{ $user->name }}">
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $user->description_score }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $user->dialogue_score }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $user->character_score }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                {{ $user->overall_superpower }}
                                            </span>
                                        </td>
                                    </tr>
                                    @php $rank++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile view -->
                    <div class="md:hidden">
                        @php $rank = 1; @endphp
                        @foreach ($users as $user)
                            @php
                                $email = strtolower(trim($user->email));
                                $emailHash = md5($email);
                                $gravatarUrl = "https://www.gravatar.com/avatar/$emailHash?s=40&d=identicon";
                            @endphp
                            <div class="bg-white shadow mb-4 rounded-lg">
                                <div class="p-4">
                                    <div class="flex items-center">
                                        <img class="h-10 w-10 rounded-full" src="{{ $gravatarUrl }}" alt="{{ $user->name }}">
                                        <div class="ml-4">
                                            <div class="text-lg font-semibold text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-600">Rank: {{ $rank }}</div>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <div class="text-sm text-gray-700"><strong>Descriptive:</strong> {{ $user->description_score }}</div>
                                        <div class="text-sm text-gray-700"><strong>Dialogue:</strong> {{ $user->dialogue_score }}</div>
                                        <div class="text-sm text-gray-700"><strong>Character:</strong> {{ $user->character_score }}</div>
                                        <div class="text-sm text-gray-700"><strong>Overall:</strong> {{ $user->overall_superpower }}</div>
                                    </div>
                                </div>
                            </div>
                            @php $rank++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
