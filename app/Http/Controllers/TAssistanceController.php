<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TAssistanceController extends Controller
{
    //
    public function techAssistanceIndex(){
        return view('techassistance.index');
    }
}
