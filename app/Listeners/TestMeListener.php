<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class TestMeListener implements ShouldQueue, ShouldBeUnique
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        sleep(5);
        //
        DB::table('test')->insert([
            'test' => $event->text
        ]);
    }
}
