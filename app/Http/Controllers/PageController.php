<?php

namespace App\Http\Controllers;


class PageController extends Controller
{

    // index page
    public function index()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('index');
    }

}
