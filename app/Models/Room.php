<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Room extends Model {
    protected $guarded = [];
    public function type() { return $this->belongsTo(RoomType::class, 'room_type_id'); }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function images() { return $this->hasMany(RoomImage::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    
    // Helper to get average rating like in your browse_rooms.php
    public function getAvgRatingAttribute() {
        return round($this->reviews()->where('is_visible', 1)->avg('rating'), 2);
    }
    // Helper for primary image
    public function getThumbAttribute() {
        return $this->images()->where('is_primary', 1)->value('filepath');
    }
}