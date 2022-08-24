<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\NotificationEvents;

class NotificationUser extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'notification_user';

    protected $fillable = [
        'notification',
        'link',
        'show',
        'user_id'
    ];
    public static function createNotification($notification){
        NotificationUser::create(
            [
                'notification' => $notification['notification'],
                'link' => $notification['link'],
                'show' => $notification['show'],
                'user_id' => $notification['user_id']
            ]);
        event(new NotificationEvents($notification['user_id']));
    }
}
