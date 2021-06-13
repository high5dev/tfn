<?php
namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\User;
use App\Models\Logg;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserStoreRequest;
use App\Http\Requests\Admin\AdminUserUpdateRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
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
     * admin: list all users
     */
    public function index()
    {
        if (Auth::User()->can('view users')) {

            $users = User::orderBy('name', 'asc')->get();

            return view('admin.users.index', compact('users'));
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view other users');
        }
    }

    /**
     * admin: display the create new user form
     */
    public function create()
    {
        if (Auth::User()->can('create users')) {

            // get a list of roles
            $roles = Role::all();

            return view('admin.users.create', compact('roles'));
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to create new users');
        }
    }

    /**
     * admin: store the new user
     */
    public function store(AdminUserStoreRequest $request)
    {
        if (Auth::User()->can('create users')) {

            // confirm admin password
            if (Hash::check($request->admin_password, Auth::User()->password)) {

                // create a new user
                $user = new User();

                // update the user
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);

                // assign roles to the new user
                $user->assignRole($request->roles);

                // save the user
                $user->save();

                // log the changes
                $log = new Logg();
                $log->title = 'Admin created user';
                $log->user_id = Auth::User()->id;
                $log->content = "Admin user created a new user:\n";
                $log->content .= print_r($user->toArray(), TRUE);
                $log->save();

                return redirect('/admin/users')->with('success', 'You have successfully created a new user');
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Incorrect password !');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to create new users');
        }
    }

    /**
     * admin: show a user
     */
    public function show($id)
    {
        if (Auth::User()->can('view users')) {

            // get the user
            $user = User::Where('id', '=', $id)->first();

            if ($user) {

                // get list of all roles
                $availableRoles = Role::all();
                // get list of roles assigned to this user
                $roles = $user->roles()->get();
                // create array of role names
                $assignedRoles = [];
                foreach ($roles as $role) {
                    $assignedRoles[] = $role->name;
                }

                return view('admin.users.show', compact('user', 'availableRoles', 'assignedRoles'));
            } else {
                return redirect('/admin/users')->with('warning', 'Unable to find that user!');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view users');
        }
    }

    /**
     * admin: update a user
     */
    public function update(AdminUserUpdateRequest $request)
    {
        if (Auth::User()->can('update users')) {

            // get the user
            $user = User::Where('id', '=', $request->id)->first();

            // confirm admin password
            if (Hash::check($request->admin_password, Auth::User()->password)) {

                // update the user
                $user->name = $request->name;
                $user->email = $request->email;

                // update the users password
                $passwordMessage = ' [password was NOT changed]';
                if (strlen($request->password)) {
                    $user->password = Hash::make($request->password);
                    $passwordMessage = ' [password WAS updated]';
                }

                // update the users roles
                $user->syncRoles($request->roles);

                if ($user->isDirty()) {

                    // save the user
                    $user->save();

                    // log the changes
                    $log = new Logg();
                    $log->title = 'Admin updated user';
                    $log->user_id = Auth::User()->id;
                    $log->content = "Admin user updated user:\nUser ID: {{ $user->id }}\n\n";
                    $log->content .= print_r($user->getChanges(), TRUE);
                    $log->save();
                }

                return redirect('/admin/users')->with('success', 'You have successfully update the user ' . $passwordMessage);
            } else {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Incorrect password !');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to update users');
        }
    }

    /**
     * admin: delete a user
     */
    public function destroy($id)
    {
        if (Auth::User()->can('delete users')) {

            // get the user
            $user = User::Where('id', '=', $id)->first();

            if ($user) {

                // get the user's name
                $name = $user->name;

                // delete the user
                $user->delete();

                // log the changes
                $log = new Logg();
                $log->title = 'Admin deleted a user';
                $log->user_id = Auth::User()->id;
                $log->content = "Admin deleted a user:\n";
                $log->content .= print_r($user->toArray(), TRUE);
                $log->save();

                return redirect('/admin/users')->with('success', 'You have successfully deleted ' . $name);
            } else {
                return redirect('/admin/users')->with('warning', 'Unable to find that user!');
            }
        } else {
            return redirect('/home')->with('error', 'Unauthorised! You need admin permission to delete other users');
        }
    }

}
