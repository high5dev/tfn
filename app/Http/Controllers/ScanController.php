<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Scan;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ScanController extends Controller
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
     * list all scan entries
     */
    public function index()
    {
        $rows = 50;

        $scans = Scan::orderBy('startid', 'desc')
            ->paginate($rows)
            ->withQueryString();

        dd($scans);

        return view('scans.index', compact('scans'));
    }

    /**
     * show a scan entry
     */
    public function show($id)
    {
        $scan = Scan::where('id', $id)->first();

        if ($scan) {
            return view('scans.show', compact('scan'));
        }
        return redirect('/scans')->with('warning', 'Unable to find that scan entry!');
    }

}
