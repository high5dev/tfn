<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use App\Models\Post;
use App\Models\Member;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
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
     * show the search index page
     */
    public function index()
    {
        // return the search view
        return view('search.index');
    }

    /**
     * search on email
     */
    public function email(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|max:254'
        ]);

        $rows = $request->user()->rows_per_page;

        $email = '%' . $request->email . '%';
        $search = 'Email: "' . $request->email . '"';

        $members = Member::where('email', 'like', $email)->paginate($rows)->withQueryString();

        if ($members) {

            // image view URL
            $imgurl = 'https://images.freecycle.org/group/x/post_image/';
            // spamtools url
            $sturl = 'https://spamcontrol.freecycle.org/';
            $stmember = 'view_member?user_id=';

            return view('search.results_email', compact('members', 'search', 'imgurl', 'sturl', 'stmember'));
        }
        return view('search.index')->with('notice', 'No results found for the search criteria');
    }

    /**
     * search on subject
     */
    public function subject(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|max:31'
        ]);

        $rows = $request->user()->rows_per_page;

        $subject = '%' . $request->subject . '%';
        $search = 'Subject: "' . $request->subject . '"';

        $posts = Post::where('subject', 'like', $subject)->orderBy('id', 'asc')->paginate($rows)->withQueryString();

        // image view URL
        $imgurl = 'https://images.freecycle.org/group/x/post_image/';
        // spamtools URL
        $sturl = 'https://spamcontrol.freecycle.org/';

        return view('search.results_subject', compact('posts', 'search', 'imgurl', 'sturl'));
    }


    /**
     * search for all new members
     */
    public function newmembers()
    {
        $rows = Auth::user()->rows_per_page;

        $members = Member::where('joined_recently', true)->paginate($rows)->withQueryString();

        if ($members) {

            // image view URL
            $imgurl = 'https://images.freecycle.org/group/x/post_image/';
            // spamtools url
            $sturl = 'https://spamcontrol.freecycle.org/';
            $stmember = 'view_member?user_id=';

            return view('search.results_email', compact('members', 'search', 'imgurl', 'sturl', 'stmember'));
        }
        return view('search.index')->with('notice', 'No new members found');
    }
}
