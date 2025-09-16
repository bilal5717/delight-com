<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;
     protected $table = 'user_status';
 protected $fillable = ['title', 'icon', 'status'];
  const STATUS_OPTIONS = [
        'new' => 'New Seller',
        'verified' => 'Verified',
        'pending' => 'Pending Verification',
        'premium' => 'Premium Seller',
        'not verified' => 'Not Verified',
    ];
     public function users()
    {
        return $this->hasMany(User::class, 'status_id');
    }
    public static function getStatusOptions()
    {
        return self::STATUS_OPTIONS;
    }
}
