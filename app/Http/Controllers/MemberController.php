<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Member;
use App\Models\Report;
use App\Actions\GetIPinfoAction;
use App\Actions\GetScamalyticsAction;
use App\Actions\GetMemberDetailsAction;
use App\Jobs\ZapMember;
use App\Http\Requests\ReportStoreRequest;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
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
     * display all members
     */
    public function index()
    {
        return view('members.index');
    }

    /**
     * index - Process ajax request
     */
    public function getMembers(Request $request)
    {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // total number of rows per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = Member::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Member::select('count(*) as allcount')
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orWhere('username', 'like', '%' . $searchValue . '%')
            ->orWhere('email', 'like', '%' . $searchValue . '%')
            ->orWhere('firstip', 'like', '%' . $searchValue . '%')
            ->count();

        // Get records with search filter
        $records = Member::orderBy($columnName, $columnSortOrder)
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orWhere('username', 'like', '%' . $searchValue . '%')
            ->orWhere('email', 'like', '%' . $searchValue . '%')
            ->orWhere('firstip', 'like', '%' . $searchValue . '%')
            ->select('*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $data_arr[] = array(
                "id" => $record->id,
                "link" => $record->link,
                "username" => $record->username,
                "email" => $record->email,
                "firstip" => $record->firstip,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr,
        );

        echo json_encode($response);
    }

    /**
     * show form to create a new member
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * store a new member
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * show form to update a member
     */
    public function show($id)
    {
        // get the member's details
        $member = Member::where('id', $id)->first();
        $imgurl = config('app.tfn_img_url');
        $sturl = config('app.tfn_base_url');

        if ($member) {
            return view('members.show', compact('member', 'imgurl', 'sturl'));
        }
        return back()->with('error', 'Member not found!');
    }

    /**
     * update a member
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * form to zap a member
     */
    public function prezap($id)
    {
        $member = Member::where('id', $id)->first();
        $imgurl = config('app.tfn_img_url');
        $sturl = config('app.tfn_base_url');

        if ($member) {
            return view('members.prezap', compact('member', 'imgurl', 'sturl'));
        }

        return back()->with('error', 'Unable to find that member!');
    }

    /**
     * zap a member
     */
    public function zap(ReportStoreRequest $request, GetMemberDetailsAction $getMember)
    {
        // confirm admin password
        if (Hash::check($request->password, Auth::User()->password)) {

            $member = Member::where('id', $request->id)->first();

            if ($member) {

                // Create zap report
                $report = Report::create(['user_id' => auth()->id] + $request->validated());
                /*
                $report = new Report;
                $report->user_id = Auth::user()->id;
                $report->member_id = $request->id;
                $report->title = $request->title;
                $report->justification = $request->justification;
                $report->found = $request->found;
                $report->warnings = $request->warnings;
                $report->warning_emails = '';
                $report->body = '';
                $report->save();
                */

                // dispatch zap job to queue
                dispatch(new ZapMember($report->id));

                return redirect('/home')->with('success', 'Successfully queued the zap');
            }

            return redirect('/home')->with('error', 'Unable to find that member, not zapped!');

        }
        return redirect()->back()
            ->withInput()
            ->with('error', 'Incorrect password !');
    }

    /**
     * delete a member and remove all their posts
     */
    public function destroy($id)
    {
        // get the post
        $member = Member::where('id', $id)->first();

        if ($member) {

            // delete all their posts if they have any
            if (isset($member->posts)) {
                $member->posts()->delete();
            }

            // now delete the member
            Member::where('id', $id)->delete();

            return back()->with('success', 'Successfully removed that member');
        }
        return redirect('/home')->with('error', 'Unable to find that member!');
    }

    /**
     * test
     */
    public function test(Request $request, GetIPinfoAction $IPinfo, GetScamalyticsAction $Scamalytics)
    {
        //$ip = $IPinfo->execute($request->ip);
        //$scam = $Scamalytics->execute($request->ip);
        //dd($ip, $scam);
        return redirect('/home')->with('success', 'Test completed');
    }
}
