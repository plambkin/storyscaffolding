<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualController extends Controller
{
    /**
     * Display the manual page.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('manual');
    }
}
