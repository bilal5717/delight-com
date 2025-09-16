<?php 

namespace App\Http\Controllers\Traits;

use Google;
use Google_Service_Indexing;
use Google_Service_Indexing_UrlNotification;

trait GoogleIndexingTrait
{
    public function setupGoogle(){
    
        $googleClient = new Google\Client();
        $credentials = json_decode(config('google-indexing.api_credentials'),true);
        $googleClient->setAuthConfig($credentials);
        $googleClient->setScopes( Google_Service_Indexing::INDEXING );

        return $googleClient;
    }
}
?>