<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Models.Tasks.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Notification.{id}', function ($user, $user_id) {
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('load.{id}', function ($user, $user_id) {
    return (int) $user->id === (int) $user_id;
});

Broadcast::channel('tasks.notification.{id}', function ($user, $user_id) {
    return (int) $user->id === (int) $user_id;
});


Broadcast::channel('message', function ($user) {
    return Auth::check();
});
