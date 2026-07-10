<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'code',
        'is_private',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'is_private' => 'boolean',
            'expired_at' => 'datetime',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expired_at', '>', now());
    }
}
