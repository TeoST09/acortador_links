<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $table = 'links';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'code',
        'url',
        'title',
        'is_active',
        'expires_at',
        'password',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
