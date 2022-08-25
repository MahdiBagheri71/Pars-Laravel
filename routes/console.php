<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
Artisan::command('say:hello {name} {description=:)}', function () {
    $this->warn("Hi ".$this->argument('name')." ".$this->argument('description'));
    $value = $this->ask('Are you ok ?(y/n)');
    if($value=='y'){
        $this->info('Good :)');
    }else{
        $this->error('Bad!!!');
    }
})->purpose('Hello');
