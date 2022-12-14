<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStatus extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'tasks_status';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'value',
        'label',
        'color',
        'sorting'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    /**
     * key value
     * @return mixed
     */
    protected function byValue(){
        return TaskStatus::select('value','label','color')->orderBy('sorting')->get()->keyBy('value')->all();
    }
}
