<?php

namespace App\Http\Controllers;

use App\Actions\GetIPinfoAction;
use App\Actions\GetScamalyticsAction;
use App\Mail\Warnings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * test
     */
    public function test(Request $request, GetIPinfoAction $IPinfo, GetScamalyticsAction $Scamalytics)
    {
        //$ip = $IPinfo->execute($request->ip);
        //$scam = $Scamalytics->execute($request->ip);
        //dd($ip, $scam);

        Mail::mailer('tfn')->from(config('tfn.mail_from'), config('tfn.mail_name'))
            ->to('chris@comgw.co.uk', 'Chris')
            ->send(new Warnings('datadatadata'));

        return redirect('/home')->with('success', 'Test completed');
    }
}
