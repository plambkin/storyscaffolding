<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    public function show($userId)
    {
        // Fetch the user-specific learning path or other relevant data
        // For now, let's just return a simple view
        return view('learning-path', ['userId' => $userId]);
    }
}

