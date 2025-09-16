<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'post_id',
        'duration_id',
        'time_slots',
        'quantity',
        'base_price',
        'addons',
        'addons_total',
        'total_price',
        'share_location',
    ];
    
    protected $casts = [
        'time_slots' => 'array',
        'addons' => 'array',
        'base_price' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'total_price' => 'decimal:2',
        'share_location' => 'boolean',
    ];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function duration()
    {
        return $this->belongsTo(PostDuration::class, 'duration_id');
    }

    public function getTimeSlotsFormattedAttribute()
    {
        if (empty($this->time_slots)) {
            return null;
        }

        $formatted = [];
        foreach ($this->time_slots as $slot) {
            if (is_array($slot)) {
                $formatted[] = [
                    'day' => $slot['day'] ?? '',
                    'open_time' => $slot['open_time'] ?? '',
                    'close_time' => $slot['close_time'] ?? ''
                ];
            }
        }

        return $formatted;
    }

    public function getAddonsFormattedAttribute()
    {
        if (empty($this->addons)) {
            return null;
        }

        $formatted = [];
        foreach ($this->addons as $addon) {
            if (is_array($addon)) {
                $formatted[] = [
                    'id' => $addon['id'] ?? null,
                    'title' => $addon['title'] ?? '',
                    'price' => $addon['price'] ?? 0
                ];
            }
        }

        return $formatted;
    }
}