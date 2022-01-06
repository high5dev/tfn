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

        $data = [
            'name' => 'Chris',
            'title' => 'smart tv',
            'date' => '6th January 2022',
        ];

        $message = (new Warnings($data))
            ->onConnection('database')
            ->onQueue('emails');

        Mail::to([
                ['email' => 'chris@comgw.co.uk', 'name' => 'Chris'],
            ])
            ->queue($message);

        return redirect('/home')->with('success', 'Test completed');
    }
}
