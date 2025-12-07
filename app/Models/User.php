<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Booking;
use App\Models\Profile;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ===================================
    //  YOUR PORTED RELATIONSHIPS (Add this block)
    // ===================================

    /**
     * Get the bookings associated with the user.
     * Maps to SELECT * FROM bookings WHERE user_id = ?
     */
    public function bookings() 
    { 
        return $this->hasMany(Booking::class); 
    }
    
    /**
     * Get the profile record associated with the user.
     * Maps to SELECT * FROM profiles WHERE user_id = ? LIMIT 1
     */
    public function profile() 
    { 
        return $this->hasOne(Profile::class); 
    }

    // Helper to get full name (from your old name_helper.php)
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }
    // ===================================
}