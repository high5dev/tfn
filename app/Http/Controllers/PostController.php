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
     * list posts
     */
    public function list(Request $request)
    {
        $validated = $request->validate([
            'postid' => 'sometimes|required|integer|min:80000000|max:99999999',
            'date' => 'sometimes|required|date_format:Y-m-d',
            'time' => 'sometimes|required|date_format:H:i'
        ]);

        $rows = 100;

        if (isset($request->postid)) {
            if ('o' == $request->type) {
                $posts = Post::where('id', '>=', $request->postid)
                    ->where('type', 'OFFER')
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            } elseif ('w' == $request->type) {
                $posts = Post::where('id', '>=', $request->postid)
                    ->where('type', 'WANTED')
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            } else {
                $posts = Post::where('id', '>=', $request->postid)
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            }
        }

        if (isset($request->date)) {
            $dated = $request->date . ' ' . $request->time . ':00';
            if ('o' == $request->type) {
                $posts = Post::where('dated', '>=', $dated)
                    ->where('type', 'OFFER')
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            } elseif ('w' == $request->type) {
                $posts = Post::where('dated', '>=', $dated)
                    ->where('type', 'WANTED')
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            } else {
                $posts = Post::where('dated', '>=', $dated)
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            }
        }

        $sturl = 'https://spamcontrol.freecycle.org/';

        return view('posts.list', compact('posts', 'sturl'));
    }

    /**
     * show the search index page
     */
    public
    function destroy($id)
    {
        // get the post
        $post = Post::where('id', $id)->first();

        if ($post) {
            Post::where('userid', $post->userid)->delete();
            return redirect('/search')->with('success', 'Successfully removed the user');
        }
        return redirect('/search')->with('error', 'Unable to find that post!');
    }
}
