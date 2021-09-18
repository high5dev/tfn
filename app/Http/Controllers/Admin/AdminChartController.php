<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Scan;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminChartController extends Controller
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
     * display weekly time spent scanning for each user
     */
    public function weekly()
    {
        if (Auth::User()->can('admin view graphs')) {
            // get all scans over last 7 days
            $scans = Scan::where('started', '>=', Carbon::today()->subDays(7))->orderBy('user_id')->get();

            // get total time scanning for each user
            $users = [];
            foreach ($scans as $scan) {
                // create user if they don't exist yet
                if (! isset($users[$scan->user_id])) {
                    $users[$scan->user_id] = 0;
                }

                // how long spent on this scan?
                $start = strtotime($scan->started);
                $stop = strtotime($scan->stopped);
                $time = $users[$scan->user_id]['time'] + abs($stop - $start);

                // save the running total
                $users[$scan->user_id] = $users[$scan->user_id] + $time;

                // build data
                $colours = $names = $time = '[';
                foreach ($users as $user) {
                    $colours .= '"#' . (string)rand(100000, 999999) . '", ';
                    $names .= '"' . $user['name'] . '", ';
                    $time .= '"' . $user['time'] . '", ';
                }

                $colours = substr($colours, 0, -2);
                $names = substr($names, 0, -2);
                $efficiency = substr($time, 0, -2);

                $colours = $colours . ']';
                $names = $names . ']';
                $time = $time . ']';

                return view('charts.users', compact('colours', 'names', 'time'));
            }
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view these graphs');
    }

    /**
     * display User's Efficiency chart
     */
    public function users()
    {
        if (Auth::User()->can('admin view graphs')) {
            // get all scans over last 30 days
            $scans = Scan::where('started', '>=', Carbon::today()->subDays(30))->orderBy('user_id')->get();

            // get number of posts scanned with time taken for each user
            $users = [];
            foreach ($scans as $scan) {
                // create user if they don't exist yet
                if (!isset($users[$scan->user_id])) {
                    $users[$scan->user_id] = [
                        'name' => '',
                        'scans' => 0,
                        'time' => 0
                    ];
                }
                // how many posts did this user scan?
                $scans = $users[$scan->user_id]['scans'] + abs($scan->stopid - $scan->startid);
                // how long did it take them?
                $start = strtotime($scan->started);
                $stop = strtotime($scan->stopped);
                $time = $users[$scan->user_id]['time'] + abs($stop - $start);
                // store
                $users[$scan->user_id] = [
                    'name' => $scan->user->name,
                    'scans' => $scans,
                    'time' => $time
                ];
            }

            // build data
            $colours = $names = $efficiency = '[';
            foreach ($users as $user) {
                $colours .= '"#' . (string)rand(100000, 999999) . '", ';
                $names .= '"' . $user['name'] . '", ';
                $efficiency .= '"' . round(($user['scans'] / $user['time']) * 100) . '", ';
            }

            $colours = substr($colours, 0, -2);
            $names = substr($names, 0, -2);
            $efficiency = substr($efficiency, 0, -2);

            $colours = $colours . ']';
            $names = $names . ']';
            $efficiency = $efficiency . ']';

            return view('charts.users', compact('colours', 'names', 'efficiency'));
        }
        return redirect('/home')->with('error', 'Unauthorised! You need admin permission to view these graphs');
    }

}
