<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Scan;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminScanController extends Controller
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
     * admin: list all scan entries
     */
    public function index()
    {
        if (Auth::User()->can('view scans')) {

            $rows = 100;

            $scans = Scan::orderBy('started', 'desc')
                ->paginate($rows)
                ->withQueryString();

            return view('admin.scans.index', compact('scans'));
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view scans');
        }
    }

    /**
     * admin: show a scan entry
     */
    public function show($id)
    {
        if (Auth::User()->can('view scans')) {

            // get the scan entry
            $scan = Scan::where('id', '=', $id)->first();

            if ($scan) {
                return view('admin.scans.show', compact('scan'));
            } else {
                return redirect('/admin/scans')->with('warning', 'Unable to find that scan entry!');
            }
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view scans');
    }
}
