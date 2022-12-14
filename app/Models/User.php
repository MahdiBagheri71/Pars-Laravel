<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',//BY mahdi for last name default value
        'username',//BY mahdi for username default value
        'is_admin',//BY mahdi for is_admin default value
        'api_token',//BY mahdi for api_token default value
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',//BY mahdi NOt Show
        // 'api_token',//BY mahdi NOt Show
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Tasks::class,'user_id', 'id');
    }


    /**
     * Get the tasks for the user.
     */
    public function tasksCreator()
    {
        return $this->hasMany(Tasks::class,'create_by_id', 'id');
    }

    /**
     * key id
     * @return mixed
     */
    protected function byID(){
        return User::all()->keyBy('id')->all();
    }
}
