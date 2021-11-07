<?php

namespace App\Http\Controllers;

use App\Http\Requests\WatchwordStoreRequest;

use Auth;
use App\Models\Logg;
use App\Models\Watchword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WatchwordController extends Controller
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
     * list all watchwords
     */
    public function index()
    {
        $watchwords = Watchword::orderBy('theword', 'asc')->get();

        return view('watchwords.index', compact('watchwords'));
    }

    /**
     * display the create new watchword form
     */
    public function create()
    {
        return view('watchwords.create');
    }

    /**
     * store the new watchword
     */
    public function store(WatchwordStoreRequest $request)
    {
        Watchword::create($request->validated());

        // logs the changes
        Logg::create([
            'title' => 'User added new watchword',
            'user_id' => Auth::user()->id,
            'content' => "User created a new watchword:\nType: " . $request->type . "\nThe word: " . $request->theword
        ]);

        return redirect('/watchwords')->with('success', 'You have successfully created a new watchword');
    }

    /**
     * delete a watchword
     */
    public
    function destroy($id)
    {
        // get the watchword
        $watchword = Watchword::where('id', $id)->first();

        if ($watchword) {

            // logs the changes
            Logg::create([
                'title' => 'User deleted a watchword',
                'user_id' => Auth::user()->id,
                'content' => print_r($watchword->toArray(), TRUE)
            ]);

            // delete the watchword
            $watchword->delete();

            return redirect('/watchwords')->with('success', 'You have successfully deleted the watchword');
        }
        return redirect('/watchwords')->with('warning', 'Unable to find that watchword!');
    }

}
