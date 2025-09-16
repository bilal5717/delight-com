<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    use Sluggable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The primary key for the model.
     *
     * @var string
     */

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'logo', 'name', 'description',
        'email', 'facebook', 'twitter', 'instagram',
        'linkedin', 'kvk', 'wechat', 'phone',
        'website', 'category_id', 'revenue',
        'registration_number', 'keywords', 'company_slug'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function companyAddresss()
    {
        return $this->hasMany(CompanyAddress::class)->orderBy('default_address', 'desc');
    }

    public function defaultCompanyAddresss()
    {
        return $this->hasOne(CompanyAddress::class)->where("default_address", 1);
    }

    public function payments()
    {
        return $this->hasMany(CompanyPayment::class)->orderBy('default_payment', 'desc');
    }

    public function companyPayment()
    {
        return $this->payments();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function sluggable(): array
    {
        return [
            'company_slug' => [
                'source' => 'name'
            ]
        ];
    }
}
