<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewUserModel extends Model
{
    use HasFactory;

    protected $table = 'view_user_model';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sorting',
        'user_id ',
        'column_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * Get the user that owns the tasks.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the user that owns the tasks.
     */
    public function columns()
    {
        return $this->belongsTo(ColumnsModel::class, 'column_id', 'id');
    }
}
