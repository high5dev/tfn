<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\Scan;
use App\Models\Statistic;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
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
     * display default chart
     */
    public function index()
    {
        $dates = Statistic::where('type', 'OFFERS')
            ->where('dated', '>=', Carbon::today()->subDays(7))
            ->where('dated', '<', Carbon::today())
            ->pluck('dated');

        $offers = Statistic::where('type', 'OFFERS')
            ->where('dated', '>=', Carbon::today()->subDays(7))
            ->where('dated', '<', Carbon::today())
            ->pluck('quantity');

        $wanteds = Statistic::where('type', 'WANTEDS')
            ->where('dated', '>=', Carbon::today()->subDays(7))
            ->where('dated', '<', Carbon::today())
            ->pluck('quantity');

        $zaps = Statistic::where('type', 'ZAPS')
            ->where('dated', '>=', Carbon::today()->subDays(7))
            ->where('dated', '<', Carbon::today())
            ->pluck('quantity');

        return view('charts.index', compact('dates', 'offers', 'wanteds', 'zaps'));
    }

    /**
     * display User's Efficiency chart
     */
    public function users()
    {
        // get all scans over last 30 days
        $scans = Scan::where('started', '>=', Carbon::today()->subDays(30))->orderBy('user_id')->get();

        // get number of posts scanned with time taken for each user
        $users = [];
        foreach ($scans as $scan) {
            // create user if they don't exist yet
            if (! isset($users[$scan->user_id])) {
                $users[$scan->user_id] = [
                    'name' => '',
                    'scans' => 0,
                    'time' => 0,
                    'eff' => 0
                ];
            }
            // how many posts did this user scan?
            $scans = $users[$scan->user_id]['scans'] + ($scan->stopid - $scan->startid);
            // how long did it take them?
            $start = strtotime($scan->started);
            $stop = strtotime($scan->stopped);
            $time = $users[$scan->user_id]['time'] + abs($stop - $start);
            // calculate total efficiency
            $eff = $users[$scan->user_id]['eff'] + ($scans/$time);
            // store
            $users[$scan->user_id] = [
                'name' => $scan->user->name,
                'scans' => $scans,
                'time' => $time,
                'eff' => $eff
            ];
        }

        // build data
        $colours = $names = $efficiency = '[';
        foreach ($users as $user) {
            $colours .= '"#' . (string)rand(100000, 999999) . '", ';
            $names .= '"' . $user['name'] .'", ';
            $efficiency .= '"' . $user['eff'] .'", ';
        }

        $colours = substr($colours, 0,-2);
        $names = substr($names, 0,-2);
        $efficiency = substr($efficiency, 0,-2);

        $colours = $colours . ']';
        $names = $names . ']';
        $efficiency = $efficiency . ']';

        return view('charts.users', compact('colours','names', 'efficiency'));
    }

}
