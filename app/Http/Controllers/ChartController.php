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
        $offers = Statistic::select(\DB::raw("COUNT(*) as count"))
            ->where('type', 'OFFERS')
            ->where('dated', '>=', Carbon::subWeek())
            ->where('dated', '<', Carbon::today())
            ->pluck('count');

        return view('charts.index', compact('offers'));
    }

}
