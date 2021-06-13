<?php
namespace App\Http\Controllers;

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
        $email = '%' . $request->email . '%';

        $posts = Post::where('email', 'like', $email)->get();

        return view('search.results', compact('posts'));
    }

    /**
     * search on subject
     */
    public function email(Request $request)
    {
        $subject = '%' . $request->subject . '%';

        $posts = Post::where('subject', 'like', $subject)->get();

        return view('search.results', compact('posts'));
    }

}
