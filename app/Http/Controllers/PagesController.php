<?php

namespace App\Http\Controllers;


class PagesController extends Controller
{

    // index page
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        } else {
            return view('index');
        }
    }

}
