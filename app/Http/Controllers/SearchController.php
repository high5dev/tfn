<?php
namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use App\Models\Post;
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

        $rows = 100;

        $email = '%' . $request->email . '%';
        $search = 'Email: ' . $request->email;

        $posts = Post::where('email', 'like', $email)->orderBy('id', 'asc')->paginate($rows)->withQueryString();

        $sturl = 'https://spamcontrol.freecycle.org/';

        return view('search.results', compact('posts', 'search', 'sturl'));
    }

    /**
     * search on subject
     */
    public function subject(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|max:31'
        ]);

        $rows = 100;

        $subject = '%' . $request->subject . '%';
        $search = 'Subject: ' . $request->subject;

        $posts = Post::where('subject', 'like', $subject)->orderBy('id', 'asc')->paginate($rows)->withQueryString();

        $sturl = 'https://spamcontrol.freecycle.org/';

        return view('search.results', compact('posts', 'search', 'sturl'));
    }

}
