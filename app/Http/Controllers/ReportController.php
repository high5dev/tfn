<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Logg;
use App\Models\Report;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReportUpdateRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

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
     * show the form to create a new zap report
     */
    public function create($member_id)
    {
        //
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
    public function update(ReportUpdateRequest $request)
    {
        // get the zap report
        $report = Report::where('id', $request->id)->first();

        if ($report) {

            // confirm password
            if (Hash::check($request->password, Auth::User()->password)) {

                // update the zap report
                $report->update($request->validated());
                /*
                $report->title = $request->title;
                $report->justification = $request->justification;
                $report->found = $request->found;
                $report->regions = $request->regions;
                $report->warnings = $request->warnings;
                */

        //        if ($report->isDirty()) {

                    // save the zap report
                    //$report->save();

                    // log the changes
                    Logg::create([
                        'user_id' => Auth::User()->id,
                        'title' => 'User updated zap report',
                        'content' => "User updated zap report:\n" .
                            "Report ID: {{ $report->id }}\n\n" .
                            print_r($report->getChanges(), true)
                    ]);
                    /*
                    $log->title = 'User updated zap report';
                    $log->user_id = Auth::User()->id;
                    $log->content = "User updated zap report:\nReport ID: {{ $report->id }}\n\n";
                    $log->content .= print_r($report->getChanges(), TRUE);
                    $log->save();
                    */
        //        }

                return redirect('/reports')->with('success', 'You have successfully updated the zap report');
            }
            return redirect()->back()->withInput()->with('error', 'Incorrect password !');
        }
        return redirect()->back()->withInput()->with('error', 'Unable to find that report!');
    }

}
