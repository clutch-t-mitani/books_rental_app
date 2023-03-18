<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\RentalStatus;
use SessionHandlerInterface;

class LogSuccessfulLogin
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
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //期日超えの本を取得
        $is_overdate_book = RentalStatus::IsOverReturnDate()->UserId($event->user->id)->get();
        session([ 'is_overdate_book' => $is_overdate_book ]);
    }
}
