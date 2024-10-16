<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AccountabilityContractController extends Controller
{
    // Show the accountability contract page
    public function show()
    {
        return view('accountability_contract');
    }

    // Handle the acceptance of the contract
    public function accept(Request $request)
    {
        $user = Auth::user();
        $user->accountability_contract_accepted = true;
        $user->save();

        return redirect()->route('accountability.start');
    }
}
