<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('dashboard', compact('user')); // Pass the user data to the view
    }
}
