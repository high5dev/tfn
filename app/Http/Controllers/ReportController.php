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
            $warning_emails = '';
            foreach(json_decode($report->warning_emails) as $email) {
                $warning_emails.= $email . ', ';
            }
            $warning_emails = substr($warning_emails, 0, -2);
            return view('reports.show', compact('report', 'warning_emails'));
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

                // log the changes
                Logg::create([
                    'user_id' => Auth::User()->id,
                    'title' => 'User updated zap report',
                    'content' => "User updated zap report:\n" .
                        "Report ID: { $report->id }\n\n" .
                        print_r($report->getChanges(), true)
                ]);

                return redirect('/reports')->with('success', 'You have successfully updated the zap report');
            }
            return redirect()->back()->withInput()->with('error', 'Incorrect password !');
        }
        return redirect()->back()->withInput()->with('error', 'Unable to find that report!');
    }

}
