<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class, 'link_id');
    }
}
