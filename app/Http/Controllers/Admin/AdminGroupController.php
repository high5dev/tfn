<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Logg;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminGroupStoreRequest;
use App\Http\Requests\Admin\AdminGroupUpdateRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminGroupController extends Controller
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
     * admin: list all groups with pagination
     */
    public function index()
    {
        if (Auth::User()->can('view groups')) {

            // get rows per page if passed with request, otherwise default to 10
            $rows = request('rows', 10);
            // don't allow > 100 rows per page
            $rows = $rows < 101 ? $rows : 100;

            $groups = Group::orderBy('name', 'asc')->paginate($rows);

            return view('admin.groups.index', compact('groups', 'rows'));
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view groups');
        }
    }

    /**
     * Admin: index - Process ajax request
     */
    public function getGroups(Request $request)
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
        $totalRecords = Group::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Group::select('count(*) as allcount')->where('name', 'like', '%' . $searchValue . '%')->count();

        // Get records with search filter
        $records = Group::orderBy($columnName, $columnSortOrder)
            ->where('groups.name', 'like', '%' . $searchValue . '%')
            ->orWhere('groups.region', 'like', '%' . $searchValue . '%')
            ->orWhere('groups.country', 'like', '%' . $searchValue . '%')
            ->select('groups.*')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "id" => $record->id,
                "link" => $record->link,
                "name" => $record->name,
                "goa" => $record->goa,
                "region" => $record->region,
                "country" => $record->country,
                "url" => $record->url,
                "contact" => $record->contact,
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
     * admin: display the create new group form
     */
    public function create()
    {
        if (Auth::User()->can('create groups')) {

            return view('admin.groups.create');
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to create new groups');
        }
    }

    /**
     * admin: store the new group
     */
    public function store(AdminGroupStoreRequest $request)
    {
        if (Auth::User()->can('create groups')) {

            // confirm admin password
            if (Hash::check($request->admin_password, Auth::User()->password)) {

                // create a new group
                $group = new Group();

                // update the group
                $group->link = $request->link;
                $group->name = $request->name;
                $group->goa = $request->goa;
                $group->region = $request->region;
                $group->country = $request->country;
                $group->url = $request->url;
                $group->contact = $request->contact;

                // save the group
                $group->save();

                // log the changes
                $log = new Logg();
                $log->title = 'Admin created group';
                $log->user_id = Auth::User()->id;
                $log->content = "Admin user created a new group:\n";
                $log->content .= print_r($group->toArray(), TRUE);
                $log->save();

                return redirect('/admin/groups')->with('success', 'You have successfully created a new group');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Incorrect password !');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to create new groups');
        }
    }

    /**
     * admin: show a group
     */
    public function show($id)
    {
        if (Auth::User()->can('view groups')) {

            // get the group
            $group = Group::Where('id', '=', $id)->first();

            if ($group) {

                return view('admin.groups.show', compact('group'));
            } else {
                return redirect('/admin/users')->with('warning', 'Unable to find that group!');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view groups');
        }
    }

    /**
     * admin: update a group
     */
    public function update(AdminGroupUpdateRequest $request)
    {
        if (Auth::User()->can('update groups')) {

            // get the group
            $group = Group::Where('id', '=', $request->id)->first();

            // confirm admin password
            if (Hash::check($request->admin_password, Auth::User()->password)) {

                // update the group
                $group->link = $request->link;
                $group->name = $request->name;
                $group->goa = $request->goa;
                $group->region = $request->region;
                $group->country = $request->country;
                $group->url = $request->url;
                $group->contact = $request->contact;

                if ($group->isDirty()) {

                    // save the group
                    $group->save();

                    // log the changes
                    $log = new Logg();
                    $log->title = 'Admin updated group';
                    $log->user_id = Auth::User()->id;
                    $log->content = "Admin user updated group:\nGroup ID: {{ $group->id }}\n\n";
                    $log->content .= print_r($group->getChanges(), TRUE);
                    $log->save();
                }

                return redirect('/admin/groups')->with('success', 'You have successfully updated the group.');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Incorrect password !');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to update groups');
        }
    }

    /**
     * admin: delete a group
     */
    public function destroy($id)
    {
        if (Auth::User()->can('delete groups')) {

            // get the group
            $group = User::Where('id', $id)->first();

            if ($group) {

                // get the group's name
                $name = $group->name;

                // delete the group
                $group->delete();

                // log the changes
                $log = new Logg();
                $log->title = 'Admin deleted a group';
                $log->user_id = Auth::User()->id;
                $log->content = "Admin deleted a group:\n";
                $log->content .= print_r($group->toArray(), TRUE);
                $log->save();

                return redirect('/admin/groups')->with('success', 'You have successfully deleted ' . $name);
            } else {
                return redirect('/admin/groups')->with('warning', 'Unable to find that group!');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to delete groups');
        }
    }

}
