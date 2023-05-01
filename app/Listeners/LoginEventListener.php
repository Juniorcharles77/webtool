<?php

namespace App\Listeners;

use App\Bitflan;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class LoginEventListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event) {
        if( $event->user->admin || $event->user->super_admin ) {
            if( File::exists( storage_path( 'bitflan/license.stp' ) ) ) {
				return;
            }

            File::put( storage_path('bitflan/lock.stp'), 'true' );
            redirect(url('/'));
        }
    }
}
