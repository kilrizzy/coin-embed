<?php

namespace App\Http\Controllers\Account\Pages;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index(){
        return view('account.page.index');
    }

}
