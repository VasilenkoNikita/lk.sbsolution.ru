<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Newsletter\NewsletterFacade as Newsletter;

class NewsLetterController extends Controller
{
    public function store(Request $request){
        if(!Newsletter::isSubscribed($request->email)){
            Newsletter::subscribePending($request->email);
            return redirect('/subscribe')->with('status', 'Проверьте вашу почту, что бы подтвердить подписку на рассылку');
        }

        return redirect('/subscribe')->with('status', 'Извините вы уже подписаны на рассылку');
    }
}
