<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Statistic;
use App\Models\Notification;

class UpdateDailyStatisticsAction
{
    public function __invoke(): void
    {
        // get yesterday
        $yesterday = Carbon::yesterday();

        // get number of OFFER posts yesterday
        $offers = Post::where('type', 'OFFER')
            ->where('dated', '>=', $yesterday)
            ->where('dated', '<', Carbon::today())
            ->count();
        // save the results
        $stats = new Statistic();
        $stats->dated = $yesterday;
        $stats->type = 'OFFERS';
        $stats->quantity = $offers;
        $stats->save();

        // get number of WANTED posts yesterday
        $wanted = Post::where('type', 'WANTED')
            ->where('dated', '>=', $yesterday)
            ->where('dated', '<', Carbon::today())
            ->count();
        // save the results
        $stats = new Statistic();
        $stats->dated = $yesterday;
        $stats->type = 'WANTEDS';
        $stats->quantity = $offers;
        $stats->save();
    }
}

