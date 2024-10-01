<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Return the view with the user's data
        return view('profile.show', compact('user'));
    }

    
}

