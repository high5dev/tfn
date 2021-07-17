<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Auth;
use App\Models\Post;
use Carbon\Carbon;
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

        if ('bypostid' == $request->posts) {

            // scan from specified post id
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

        } elseif ('bydatetime' == $request->posts) {

            // scan from specified datetime
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

        } else {

            // scan from midnight today
            $dated = date('Y-m-d 00:00:00');
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

        // is someone scanning?
        $scanning = '';
        if ($request->scanning) {
            Scan::create([
                'user_id' => Auth::user()->id,
                'started' => Carbon::now()
            ]);
            session(['scanning' => Auth::user()->name]);
        }

        // Spamtool URL to individual posts can be viewed
        $sturl = 'https://spamcontrol.freecycle.org/';

        return view('posts.list', compact('posts', 'sturl'));
    }

    /**
     * flag that the user has finished scanning
     */
    public function doneScanning()
    {
        // get the current scanning entry
        $scan = Scan::where('user_id', Auth::user()->id)->whereNull('finished')->first();
        $scan->finished = Carbon::now();
        $scan->save();

        session()->forget('scanning');

        return view('/home')->with('success', 'Thank you for your scanning session, it is most appreciated!');
    }

    /**
     * delete a post entry
     */
    public function destroy($id)
    {
        // get the post
        $post = Post::where('id', $id)->first();

        if ($post) {
            Post::where('userid', $post->userid)->delete();
            return redirect('/search')->with('success', 'Successfully removed that post');
        }
        return redirect('/search')->with('error', 'Unable to find that post!');
    }
}
