<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $fillable = [
        'room_id',
        'filepath',
        'alt_text',
        'is_primary',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
