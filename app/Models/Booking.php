<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    protected $guarded = [];
    protected $casts = ['start_time' => 'datetime', 'end_time' => 'datetime'];
    public function room() { return $this->belongsTo(Room::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function roomType()
    {
        return $this->room->type();
    }
}
