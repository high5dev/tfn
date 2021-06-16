<?php

namespace App\Http\Controllers;

use Auth;
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

        return view('charts.index', compact('dates', 'offers', 'wanteds'));
    }

}
