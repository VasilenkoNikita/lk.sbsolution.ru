<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Newsletter\Newsletter;

class SubscribeController extends Controller
{
    public function index()
    {
        return view('mailchimp.subscribe', [

        ]);
    }
}
