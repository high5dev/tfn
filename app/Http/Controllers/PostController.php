<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Post;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
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
    public function destroy($id)
    {
        // get the post
        $post = Post::where('id', $id)->first();

        if ($post) {
            $post->delete();
            return view('search.index')->with('success', 'Successfully removed the post');
        }
        return view('search.index')->with('error', 'Unable to find that post!');
    }
}
