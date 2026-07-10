<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'session_id',
        'name',
        'last_seen',
        'typing_at',
    ];

    protected function casts(): array
    {
        return [
            'last_seen' => 'datetime',
            'typing_at' => 'datetime',
        ];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeOnline($query)
    {
        return $query->where('last_seen', '>=', now()->subSeconds(30));
    }

    public function scopeTyping($query)
    {
        return $query->where('typing_at', '>=', now()->subSeconds(3));
    }
}
