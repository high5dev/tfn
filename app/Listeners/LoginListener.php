<?php

namespace App\Listeners;

use Auth;
use App\Models\Logg;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LoginListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        // get user
        $user = $event->user;

        // reset lastlogin session
        session([
            'lastLogin' => null
        ]);

        // if user has a valid last login date/time set the session variable
        if (preg_match('/\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d/', $user->last_login_at)) {
            session([
                'lastLogin' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login_at)
            ]);
        }

        // update db
        $user->last_login_at = date('Y-m-d H:i:s');
        $user->last_login_ip = $this->request->ip();
        $user->save();

        // logs the login
        $log = new Logg();
        $log->title = Auth::User()->name . ' logged in';
        $log->user_id = Auth::User()->id;
        $log->content = Auth::User()->name . " logged in at " . date('Y-m-d H:i:s') . " from IP: " . $this->request->ip();
        $log->save();
    }
}
