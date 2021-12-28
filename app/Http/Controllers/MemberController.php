<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Member;
use App\Models\Report;
use App\Actions\GetIPinfoAction;
use App\Actions\GetScamalyticsAction;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
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
        //
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

        if($member) {
            return view('members.show', compact('member'));
        }
        return back()->with('error', 'Mmeber not found!');
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

        if ($member) {
            return view('members.prezap', compact('member'));
        }

        return back()->with('error', 'Unable to find that member!');
    }

    /**
     * zap a member
     */
    public function zap(Request $request)
    {
        /**
         * <form method="post" action="https://spamcontrol.freecycle.org/zap_member">
         * <input type='hidden' name='user_id' id='user_id' value="31465118" />
         * <input type='submit' value="Zap Member" />
         * </form>
         */

        $member = Member::where('id', $request->id)->first();

        if($member) {

            // TODO: Send zap request to SpamTool

            // Create zap report
            $report = Report::create($request->validated());

            // delete all their posts if they have any
            if(isset($member->posts)) {
                $member->posts()->delete();
            }

            // delete the member
            $member->delete();

            return back()->with('success', 'Successfully zapped the member, all posts removed');
       }

        return redirect('/home')->with('error', 'Unable to find that member, not zapped!');
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
            if(isset($member->posts)) {
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
        $ip = $IPinfo->execute($request->ip);

        $scam = $Scamalytics->execute($request->ip);

        dd($ip, $scam);
    }
}
