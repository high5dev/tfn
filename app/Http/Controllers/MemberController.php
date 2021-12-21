<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Member;
use App\Actions\GetIPinfoAction;
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
        $rows = Auth::user()->rows_per_page;

        $members = Member::orderBy('username', 'asc')->paginate($rows)->withQueryString();

        return view('members.index', compact('members'));
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
        //
    }

    /**
     * update a member
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * zap a member
     */
    public function zap($id)
    {
        /**
         * <form method="post" action="https://spamcontrol.freecycle.org/zap_member">
         * <input type='hidden' name='user_id' id='user_id' value="31465118" />
         * <input type='submit' value="Zap Member" />
         * </form>
         */

        $member = Member::where('id', $id)->first();

        if($member) {
            $response = Http::asForm()->post('https://spamcontrol.freecycle.org/zap_member', [
                'user_id' => $id,
            ]);

            dd($response);

            // delete all their posts if they have any
            if(isset($member->posts)) {
                $member->posts()->delete();
            }

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
    public function test(Request $request, GetIPinfoAction $IPinfo)
    {
        $results = $IPinfo->execute($request->ip);

        dd($results);
    }
}