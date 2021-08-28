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

        if ('last' == $request->posts) {

            // get the last scanned post ID
            $lastScanned = Scan::orderBy('stopid', 'desc')->first();

            // scan from last scanned post id
            if ('o' == $request->type) {
                $posts = Post::where('id', '>=', $lastScanned->stopid)
                    ->where('type', 'OFFER')
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            } elseif ('w' == $request->type) {
                $posts = Post::where('id', '>=', $lastScanned->stopid)
                    ->where('type', 'WANTED')
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            } else {
                $posts = Post::where('id', '>=', $lastScanned->stopid)
                    ->orderBy('dated', 'asc')
                    ->paginate($rows)
                    ->withQueryString();
            }

        } elseif ('bypostid' == $request->posts) {

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

        // is the user scanning?
        $alreadyScanning = Scan::where('user_id', Auth::user()->id)->whereNull('stopped')->first();
        if (!$alreadyScanning) {
            if ($request->scanning) {
                Scan::create([
                    'user_id' => Auth::user()->id,
                    'started' => Carbon::now(),
                    'startid' => $posts->first()->id
                ]);
                session(['scanning' => Auth()->user()->name]);
            }
        }

        // Spamtool URL to individual posts can be viewed
        $sturl = 'https://spamcontrol.freecycle.org/';

        return view('posts.list', compact('posts', 'sturl'));
    }

    /**
     * list potential spam posts
     */
    public function spam(Request $request)
    {
        $rows = 100;

        $posts = Post::where('spam', 1)
            ->orderBy('dated', 'asc')
            ->paginate($rows)
            ->withQueryString();

        // Spamtool URL to individual posts can be viewed
        $sturl = 'https://spamcontrol.freecycle.org/';

        return view('posts.spam', compact('posts', 'sturl'));
    }

    /**
     * mark posts as not spam
     */
    public function notSpam($id)
    {
        // update the post
        Post::where('id', $id)->update(['spam' => 0]);

        return back()->with('success', 'Successfully unmarked that post as spam');
    }

    /**
     * flag that the user has finished scanning
     */
    public function doneScanning(Request $request)
    {
        // get the current scanning entry
        $scan = Scan::where('user_id', Auth::user()->id)->whereNull('stopped')->first();
        if ($scan) {
            $scan->stopid = $request->id;
            $scan->stopped = Carbon::now();
            $scan->save();
            return redirect('/posts/summary/' . $scan->id);
        }
        return redirect('/home')->with('error', "I don't think you were scanning!");
    }

    /**
     * debrief a scanning session
     */
    public function showSummary($id)
    {
        // get the scanning session
        $scan = Scan::where('user_id', Auth::user()->id)->where('id', $id)->first();
        if ($scan) {
            return view('posts.summary', compact('id'));
        }
        return redirect('/home')->with('error', "Unable to find that scan entry!");
    }

    /**
     * save the summary page
     */
    public function saveSummary(Request $request)
    {
        // get the scan entry
        $scan = Scan::where('user_id', Auth::user()->id)->where('id', $request->id)->first();
        if ($scan) {
            $scan->stopid = $request->zaps;
            $scan->stopped = $request->notes;
            $scan->save();
            return redirect('/home')->with('success', 'Thank you for scanning, your efforts are appreciated');
        }
        return redirect('/home')->with('error', "Unable to find that scan entry!");
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
            return back()->with('success', 'Successfully removed that post');
        }
        return redirect('/home')->with('error', 'Unable to find that post!');
    }
}
