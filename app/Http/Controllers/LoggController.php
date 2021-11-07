<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Logg;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoggController extends Controller
{

    /**
     * create a new controller instance.
     */
    public function __construct()
    {
        // user can only access this if they're logged in and verified
        $this->middleware([
            'auth'
        ]);
    }

    /**
     * list all the logged in user's logs entries
     */
    public function index()
    {
        $logs = Logg::Where('user_id', '=', Auth::User()->id)->orderBy('created_at', 'desc')->get();

        return view('logs.index', compact('logs'));
    }

    /**
     * show a user's logs entry
     */
    public function show($id)
    {

        // get the logs entry
        $log = Logg::Where('user_id', '=', Auth::User()->id)->where('id', '=', $id)->first();

        if ($log) {
            return view('logs.show', compact('log'));
        }
        return redirect('/logs')->with('warning', 'Unable to find that logs entry!');
    }
}
