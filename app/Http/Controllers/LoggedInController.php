<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Auth;
use Storage;
use Validator;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Logg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LoggedInController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // user can only access this if they're logged in
        $this->middleware([
            'auth'
        ]);
    }

    // home page
    public function home()
    {
        // get users name
        $name = Auth::user()->name;

        // get the last datetime they logged in
        $lastLoggedIn = '';
        if (is_object(session('lastLogin'))) {
            $lastLoggedIn = session('lastLogin')->format('l jS F Y \a\t g:i a');
        }

        // get number of OFFER posts in the past 24 hours
        $offers = Post::where('type', 'OFFER')->where('dated', '>=', Carbon::now()->subDay())->count();
        $offers = number_format($offers);

        // get number of WANTED posts in the past 24 hours
        $wanteds = Post::where('type', 'WANTED')->where('dated', '>=', Carbon::now()->subDay())->count();
        $wanteds = number_format($wanteds);

        // are they marked as scanning?
        $scanning = Scan::where('user_id', Auth::user()->id)
            ->whereNull('finished')
            ->orderBy('id', 'asc')->first();
        $scanStarted = '';
        if($scanning) {
            $scanStarted = $scanning->started->format('l jS F Y \a\t g:i a');
        }

        return view('home', compact('name', 'lastLoggedIn', 'offers', 'wanteds','scanStarted'));
    }

    // log the user out and redirect to index page
    public function logout()
    {
        // log the logout
        $log = new Logg();
        $log->title = Auth::User()->FullName . ' logged out';
        $log->user_id = Auth::User()->id;
        $log->content = Auth::User()->FullName . " logged out at " . date('Y-m-d H:i:s');
        $log->save();

        // log user out
        auth()->logout();
        session()->flush();
        return redirect()->route('index');
    }
}
