<?php
namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Logg;
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

            $users = User::orderBy('family_name', 'asc')->orderBy('given_name', 'asc')->get();

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
                $user->company = $request->company;
                $user->given_name = $request->given_name;
                $user->family_name = $request->family_name;
                $user->phone = $request->phone;
                $user->mobile = $request->mobile;
                $user->address = $request->address;
                $user->town = $request->town;
                $user->county = $request->county;
                $user->postcode = $request->postcode;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->setup_completed = $request->setup_completed;
                $user->adapter_posted = $request->adapter_posted;
                $user->direct_debit = $request->direct_debit;

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
                $user->company = $request->company;
                $user->given_name = $request->given_name;
                $user->family_name = $request->family_name;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->mobile = $request->mobile;
                $user->address = $request->address;
                $user->town = $request->town;
                $user->county = $request->county;
                $user->postcode = $request->postcode;
                $user->setup_completed = $request->setup_completed;
                $user->adapter_posted = $request->adapter_posted;
                $user->direct_debit = $request->direct_debit;

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

                // check if the user is linked to any routers
                if ( count($user->routers) ) {
                    return redirect('/admin/users')->with('error', 'The user is linked to one or more routers, please remove then try again');
                }

                // check if the user is linked to any numbers
                if ( count($user->numbers) ) {
                    return redirect('/admin/users')->with('error', 'The user is linked to one or more numbers, please remove then try again');
                }

                // get the user's name
                $name = $user->given_name . ' ' . $user->family_name;

                // reset any voicemail entry
                $user->voicemails->user_id = 0;
                $user->voicemails->fullname = '';
                $user->voicemails->email = '';

                // delete any notes
                $user->notes()->delete();

                // delete any emergency entry
                $user->emergencies()->delete();

                // delete any redirects
                $user->redirects()->delete();

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
