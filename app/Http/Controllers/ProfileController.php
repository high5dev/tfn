<?php
namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Logg;
use App\Http\Requests\ProfileUpdateRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
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
     * show the users profile page
     */
    public function show()
    {
        // get currently logged in user
        $user = Auth::User();

        // return their profile view
        return view('profile', compact('user'));
    }

    /**
     * update users profile
     */
    public function update(ProfileUpdateRequest $request)
    {
        // get the user
        $user = Auth::User();

        if (Hash::check($request->current_password, $user->password)) {

            // check if password needs to be re-hashed
            if (Hash::needsRehash($user->password)) {
                $user->password = Hash::make($request->current_password);
            }

            // update the user
            $user->name = $request->name;
            $user->email = $request->email;

            // update the users password
            $passwordMessage = ' [password was NOT changed]';
            if (strlen($request->password)) {
                $user->password = Hash::make($request->password);
                $passwordMessage = ' [password WAS updated]';
            }

            if ($user->isDirty()) {

                // update the user
                $user->save();

                // log the changes
                $log = new Logg();
                $log->title = 'User updated their profile';
                $log->user_id = $user->id;
                $log->content = "User updated their profile:\n";
                $log->content .= print_r($user->getChanges(), TRUE);
                $log->save();

                // redirect back to homepage
                return redirect('/home')->with('success', 'You have successfully updated your profile ' . $passwordMessage);
            }
            // redirect back to homepage
            return redirect('/home')->with('success', 'You made no changes, nothing updated');
        } else {
            // wrong password
            return redirect()->back()->with('error', 'Incorrect password !!');
        }
    }
}
