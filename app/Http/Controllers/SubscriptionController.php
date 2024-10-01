<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function showUpgradePage()
    {
        return view('subscription.upgrade'); // You'll need to create this view
    }
}
