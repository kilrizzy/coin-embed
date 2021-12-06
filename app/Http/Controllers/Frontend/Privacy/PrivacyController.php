<?php

namespace App\Http\Controllers\Frontend\Privacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function index(){
        return view('frontend.privacy.index');
    }
}
