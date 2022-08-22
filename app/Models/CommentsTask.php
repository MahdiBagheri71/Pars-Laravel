<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentsTask extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'comments_task';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'note',
        'task_id',
        'create_by_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * Get the user creator that owns the tasks.
     */
    public function creator()
    {
        return $this->belongsTo(User::class,'create_by_id', 'id');
    }
}
