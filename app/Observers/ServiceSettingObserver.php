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

namespace App\Observers;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\ServiceSettings;
use Illuminate\Support\Facades\Cache;

class ServiceSettingObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param ServiceSettings $serviceSetting
     * @return void
     */
    public function deleting($serviceSetting)
    {

    }

    /**
     * Listen to the Entry saved event.
     *
     * @param ServiceSettings $serviceSetting
     * @return void
     */
    public function saved(ServiceSettings $serviceSetting)
    {
        // Removing Entries from the Cache
        $this->clearCache($serviceSetting);
    }

    /**
     * Listen to the Entry deleted event.
     *
     * @param ServiceSettings $serviceSetting
     * @return void
     */
    public function deleted(ServiceSettings $serviceSetting)
    {
        // Removing Entries from the Cache
        $this->clearCache($serviceSetting);
    }

    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $serviceSetting
     */
    private function clearCache($serviceSetting)
    {
        Cache::flush();
    }
}