<?php

namespace App\Http\Controllers\Frontend\Terms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function index(){
        return view('frontend.terms.index');
    }
}
