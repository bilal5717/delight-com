<?php
/**
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use App\Observers\ServiceSettingObserver;
use Larapen\Admin\app\Models\Traits\Crud;
use Larapen\Admin\app\Models\Traits\SpatieTranslatable\HasTranslations;

class ServiceSettings extends BaseModel
{
    use Crud, HasTranslations;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_settings_info';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    // public $timestamps = false;

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
        'setting_key',
        'setting_value',
        'active',
    ];
    public $translatable = ['setting_value'];

    /**
     * The attributes that should be hidden for arrays
     *
     * @var array
     */
    // protected $hidden = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        ServiceSettings::observe(ServiceSettingObserver::class);

        static::addGlobalScope(new ActiveScope());
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    // The slug is created automatically from the "name" field if no slug exists.

    public function getSettingKeyAttribute($value)
    {
        if (isset($this->attributes['setting_key']) && !isValidJson($this->attributes['setting_key'])) {
            return $this->attributes['setting_key'];
        }

        return $value;
    }

    public function getSettingValueAttribute($value)
    {
        if (isset($this->attributes['setting_value']) && !isValidJson($this->attributes['setting_value'])) {
            return $this->attributes['setting_value'];
        }

        return $value;
    }

    public function getFallbackValue($value)
    {
        $translations = json_decode($this->attributes['setting_value'], true);
        $locale = app()->getLocale();

        if (isset($translations[$locale]) && !empty($translations[$locale])) {
            return $translations[$locale];
        }

        return $translations['en'] ?? null;
    }
}