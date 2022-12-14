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
     * admin: list all logs entries
     */
    public function index()
    {
        if (Auth::User()->can('view logs')) {

            $rows = Auth::user()->rows_per_page;

            // get rows per page if passed with request
            $rows = request('rows', $rows = $rows);

            // don't allow > 100 rows per page
            $rows = $rows < 101 ? $rows : 100;

            $logs = Logg::orderBy('created_at', 'desc')->paginate($rows);

            return view('admin.logs.index', compact('logs', 'rows'));
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view logs');
    }

    /**
     * admin: show a logs entry
     */
    public function show($id)
    {
        if (Auth::User()->can('view logs')) {

            // get the logs entry
            $log = Logg::Where('id', '=', $id)->first();

            if ($log) {
                return view('admin.logs.show', compact('log'));
            }
            return redirect('/admin/logs')->with('warning', 'Unable to find that logs entry!');
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view logs');
    }
}
