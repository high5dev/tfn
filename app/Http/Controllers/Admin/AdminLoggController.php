<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Logg;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminLoggController extends Controller
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
     * admin: list all log entries
     */
    public function index()
    {
        if (Auth::User()->can('view logs')) {

            $logs = Logg::orderBy('created_at', 'desc')->get();

            return view('admin.log.index', compact('logs'));
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view logs');
        }
    }

    /**
     * admin: show a log entry
     */
    public function show($id)
    {
        if (Auth::User()->can('view logs')) {

            // get the log entry
            $log = Logg::Where('id', '=', $id)->first();

            if ($log) {
                return view('admin.log.show', compact('log'));
            } else {
                return redirect('/admin/logs')->with('warning', 'Unable to find that log entry!');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view logs');
        }
    }
}
