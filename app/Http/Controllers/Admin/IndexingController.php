<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Google;
use Google_Service_Indexing;
use Larapen\Admin\app\Http\Controllers\PanelController;
use Larapen\Admin\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Response;
use App\Http\Controllers\Traits\GoogleIndexingTrait;

class IndexingController extends PanelController
{
   use GoogleIndexingTrait;

    public function index(){
        
        $this->xPanel->setRoute(admin_uri('dashboard'));
        $this->data['xPanel'] = $this->xPanel;

        return view('indexing.index',$this->data);
    }

    public function status(Request $request){
        

        $request->validate([
            'url' => 'required|url'
        ]);

        try {
            
            $googleClient = $this->setupGoogle();
            
            $googleIndexingService = new Google_Service_Indexing( $googleClient );
        
            $url = ["url" => $request->url];
        
            $result = $googleIndexingService->urlNotifications->getMetadata( $url );
            
            return redirect()->back()->with('status',$result);
        } 
        catch (\Exception $e) {
            
            $message = $e->getMessage();
            $dec = json_decode($message, true);
            $dec['url'] = $request->url;
            return redirect()->back()->with('failed',$dec);
        }
    }
}
