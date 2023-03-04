<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PerevozchikiController extends Controller
{
    public function perevozchiki(Request $request)
    {
        return view('front.perevozchiki')->with('auth_user',  auth()->user());
    }
}
