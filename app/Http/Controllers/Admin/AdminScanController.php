<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Scan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ScanUpdateRequest;
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

    /**
     * admin: update a scan entry
     */
    public function update(ScanUpdateRequest $request)
    {
        if (Auth::User()->can('update scans')) {

            // get the scan entry
            $scan = Scan::Where('id', '=', $request->id)->first();

            // confirm admin password
            if (Hash::check($request->admin_password, Auth::User()->password)) {

                // update the scan entry
                $scan->started = $request->started;
                $scan->stopped = $request->stopped;
                $scan->startid = $request->startid;
                $scan->stopid = $request->stopid;
                $scan->startts = $request->startts;
                $scan->stopts = $request->stopts;
                $scan->zaps = $request->zaps;
                $scan->notes = $request->notes;

                if ($scan->isDirty()) {

                    // save the scan
                    $scan->save();

                    // logs the changes
                    $log = new Logg();
                    $log->title = 'Admin updated scan';
                    $log->user_id = Auth::User()->id;
                    $log->content = "Admin user updated scan:\nScan ID: {{ $scan->id }}\n\n";
                    $log->content .= print_r($scan->getChanges(), TRUE);
                    $log->save();
                }

                return redirect('/admin/scans')->with('success', 'You have successfully update the scan entry');
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Incorrect password !');
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to update scans');

    }

}
