<?php

namespace App\Http\Controllers\Teach;

use App\Http\Controllers\Controller;

class AppController extends Controller
{    
    public function app(){
        return view('teach.app');
    }
}