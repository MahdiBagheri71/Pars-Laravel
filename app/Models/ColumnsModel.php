<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColumnsModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'columns_model';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'field',
        'table',
        'model',
        'label',
        'type ',
        'related_table ',
        'related_field ',
        'related_show '
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];
}
