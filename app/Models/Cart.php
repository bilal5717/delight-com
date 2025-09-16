<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'carts';

    protected $fillable = [
        'user_id',
        'post_id',
        'duration_id',
        'quantity',
        'base_price',
        'addons',
        'time_slots',
        'addons_total',
        'total_price',
        'order',
    ];
    protected $casts = [
        'addons' => 'json',
        'time_slots' => 'array', 
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function duration()
{
    return $this->belongsTo(PostDuration::class, 'duration_id');
}
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
}
