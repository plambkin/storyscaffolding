<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class LeaderboardController extends Controller
{
    public function showLeaderboard()
    {
        // Get all users with their scores and email
        $users = User::select('id', 'name', 'email', 'descriptive_score', 'dialogue_score', 'character_score')
                    ->get()
                    ->map(function($user) {
                        // Calculate the overall superpower
                        $user->overall_superpower = $user->descriptive_score + $user->dialogue_score + $user->character_score;
                        return $user;
                    });

        return view('leaderboard', compact('users'));
    }
}
