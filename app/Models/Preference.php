<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    //

    protected $fillable = [
        'user_id',
        'categories',
        'sources',
        'authors',
    ];

    protected $casts = [
        'categories' => 'array',
        'sources' => 'array',
        'authors' => 'array',
    ];
}