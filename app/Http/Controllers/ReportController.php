<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Logg;
use App\Models\Report;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
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
     * list all zap reports
     */
    public function index()
    {
        $rows = Auth::user()->rows_per_page;

        $reports = Report::orderBy('id', 'desc')
            ->paginate($rows)
            ->withQueryString();

        return view('reports.index', compact('reports'));
    }

    /**
     * show a zap report
     */
    public function show($id)
    {
        // get the zap report
        $report = Report::where('id', $id)->first();

        if ($report) {
            return view('reports.show', compact('report'));
        }
        return redirect('/reports')->with('warning', 'Unable to find that zap report!');
    }

    /**
     * update a zap report
     */
    public function update(ScanUpdateRequest $request)
    {
        // get the zap report
        $report = Report::where('id', $request->id)->first();

        // confirm password
        if (Hash::check($request->password, Auth::User()->password)) {

            // update the zap report
            $report->started = $request->justification;
            $report->stopped = $request->found;
            $report->startid = $request->regions;

            if ($report->isDirty()) {

                // save the zap report
                $report->save();

                // log the changes
                $log = new Logg();
                $log->title = 'User updated zap report';
                $log->user_id = Auth::User()->id;
                $log->content = "User updated zap report:\nReport ID: {{ $report->id }}\n\n";
                $log->content .= print_r($report->getChanges(), TRUE);
                $log->save();
            }

            return redirect('/reports')->with('success', 'You have successfully updated the zap report');
        }
        return redirect()->back()
            ->withInput()
            ->with('error', 'Incorrect password !');
    }

}
