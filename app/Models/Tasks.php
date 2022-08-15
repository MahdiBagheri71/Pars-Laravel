<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use HasFactory,SoftDeletes;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'note',
        'status',
        'date',
        'time',
        'user_id',
        'create_by_id',
    ];

     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Get the user that owns the tasks.
     */
    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    /**
     * Get the user creator that owns the tasks.
     */
    public function creator()
    {
        return $this->belongsTo(User::class,'create_by_id', 'id');
    }
}
