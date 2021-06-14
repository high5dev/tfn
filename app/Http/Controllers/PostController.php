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
     * get ready to display all posts
     */
    public function index()
    {
        return view('posts.index');
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
            return redirect('/search')->with('success', 'Successfully removed the post');
        }
        return redirect('/search')->with('error', 'Unable to find that post!');
    }
}
