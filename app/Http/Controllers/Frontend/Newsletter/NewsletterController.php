<?php

namespace App\Http\Controllers\Frontend\Newsletter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Newsletter;

class NewsletterController extends Controller
{
    public function index(){
        return view('frontend.newsletter.index');
    }

    public function store(Request $request){
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        if (!Newsletter::isSubscribed($request->get('email'))) {
            Newsletter::subscribeOrUpdate($request->get('email'), []);
        }
        return redirect('/newsletter/complete');
    }

    public function show(){
        return view('frontend.newsletter.show');
    }
}
