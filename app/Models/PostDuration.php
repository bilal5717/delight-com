<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostDuration extends Model
{
    use HasFactory;
    protected $table = 'post_durations';

    protected $fillable = [
        'post_id',
        'location_id',
        'duration_title',
        'duration_unit',
        'duration_value',
        'available_units',
        'booked_units',
        'max_capacity',
        'is_active',
        'open_time', 
    ];


    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(\App\Models\ShippingAddress::class, 'location_id', 'shipping_id');
    }
}