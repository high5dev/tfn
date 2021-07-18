<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Logg;
use App\Models\Notification;

class UpdateDailyStatisticsAction
{
    public function __invoke(): void
    {
        // delete any posts over 30 days old
        $posts = Post::where('dated', '<', Carbon::now()->subDays(30))->delete();

        // Log it
        Logg::create([
            'type' => 'admin',
            'title' => 'Posts pruned',
            'user_id' => 0,
            'target_id' => 0,
            'content' => "Old posts pruned:\n" . print_r($posts->attributesToArray(), true)
        ]);
    }
}
