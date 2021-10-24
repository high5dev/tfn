<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Session;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminSessionController extends Controller
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
     * admin: list all sessions
     */
    public function index()
    {
        dd('ok');
        if (Auth::User()->can('view sessions')) {

            $sessions = Session::orderBy('last_activity', 'desc')->get();

            return view('admin.sessions.index', compact('sessions'));
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view sessions');
    }

    /**
     * admin: show a session
     */
    public function show($id)
    {
        if (Auth::User()->can('view sessions')) {

            // get the session
            $session = Session::Where('id', '=', $id)->first();

            if ($session) {
                return view('admin.sessions.show', compact('session'));
            }
            return redirect('/admin/sessions')->with('warning', 'Unable to find that session!');
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view sessions');
    }

    /**
     * delete a session
     */
    public function destroy($id)
    {
        if (Auth::User()->can('view sessions')) {

            // get the session
            $session = Session::Where('id', '=', $id)->first();

            if ($session) {

                // delete the session
                $session->delete();

                return redirect('/admin/sessions')->with('success', 'The session was deleted');
            }
            return redirect('/admin/sessions')->with('warning', 'Unable to find that session!');
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to kick sessions');
    }
}
