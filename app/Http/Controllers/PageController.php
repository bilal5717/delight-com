<?php
/**
 * 
 */

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Traits\Sluggable\PageBySlug;
use App\Http\Requests\ContactRequest;
use App\Models\City;
use App\Models\Package;
use App\Models\Permission;
use App\Models\User;
use App\Notifications\FormSent;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;

class PageController extends FrontController
{
	use PageBySlug;
	
	/**
	 * ReportController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware('demo.restriction')->only(['contactPost']);
	}
	
	/**
	 * @return mixed
	 */
	public function pricing()
	{
		$data = [];
		
		// Get Packages
		$cacheId = 'packages.with.currency.' . config('app.locale');
		$packages = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$packages = Package::applyCurrency()->with('currency')->orderBy('lft')->get();
			
			return $packages;
		});
		$data['packages'] = $packages;
		
		// Select a Package and go to previous URL ---------------------
		// Add Listing possible URLs
		$addListingUriArray = [
			'create',
			'post\/create',
			'post\/create\/[^\/]+\/photos',
		];
		// Default Add Listing URL
		$addListingUrl = UrlGen::addPost();
		if (request()->filled('from')) {
			foreach ($addListingUriArray as $uriPattern) {
				if (preg_match('#' . $uriPattern . '#', request()->get('from'))) {
					$addListingUrl = url(request()->get('from'));
					break;
				}
			}
		}
		view()->share('addListingUrl', $addListingUrl);
		// --------------------------------------------------------------
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'pricing'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'pricing')));
		MetaTag::set('keywords', getMetaTag('keywords', 'pricing'));
		
		return appView('pages.pricing', $data);
	}
	
	/**
	 * @param $slug
	 * @return \Illuminate\Http\RedirectResponse|mixed
	 */
	public function cms($slug)
	{
		// Get the Page
		$page = $this->getPageBySlug($slug);
		if (empty($page)) {
			abort(404);
		}
		
		view()->share('page', $page);
		view()->share('uriPathPageSlug', $slug);
		
		// Check if an external link is available
		if (!empty($page->external_link)) {
			return redirect()->away($page->external_link, 301)->withHeaders(config('larapen.core.noCacheHeaders'));
		}
		
		// SEO
		$title = $page->title;
		$description = Str::limit(str_strip($page->meta_description), 200);
		$keywords = Str::limit(str_strip($page->meta_keywords));

		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		MetaTag::set('keywords', $keywords);

		// Open Graph
		$this->og->title($title)->description($description);
		if (!empty($page->picture)) {
			if ($this->og->has('image')) {
				$this->og->forget('image')->forget('image:width')->forget('image:height');
			}
			$this->og->image(imgUrl($page->picture, 'bgHeader'), [
				'width'  => 600,
				'height' => 600,
			]);
		}
		view()->share('og', $this->og);
		
		return appView('pages.cms');
	}
	
	
    /**
     * Handle cms page with language parameter
     * 
     * @param $slug
     * @param $code
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function cmsWithLang($slug, $code)
    {
        if ($code && isAvailableLang($code)) {
            session()->put('langCode', $code);
            app()->setLocale($code);
        }
        
        return $this->cms($slug);
    }	
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function contact()
	{
		// Get the Country's largest city for Google Maps
		$cacheId = config('country.code') . '.city.population.desc.first';
		$city = Cache::remember($cacheId, $this->cacheExpiration, function () {
			$city = City::currentCountry()->orderBy('population', 'desc')->first();
			
			return $city;
		});
		view()->share('city', $city);
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'contact'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
		MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
		
		return appView('pages.contact');
	}
	
	/**
	 * @param ContactRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function contactPost(ContactRequest $request)
	{
		// Store Contact Info
		$contactForm = $request->all();
		$contactForm['country_code'] = config('country.code');
		$contactForm['country_name'] = config('country.name');
		$contactForm = ArrayHelper::toObject($contactForm);
		
		// Send Contact Email
		try {
			if (config('settings.app.email')) {
				Notification::route('mail', config('settings.app.email'))->notify(new FormSent($contactForm));
			} else {
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new FormSent($contactForm));
				}
			}
			flash(t('message_sent_to_moderators_thanks'))->success();
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
		}
		
		return redirect(UrlGen::contact());
	}
	
    /**
     * Handle contact page with language parameter
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function contactWithLang($code)
    {

        if ($code && isAvailableLang($code)) {
            session()->put('langCode', $code);
            app()->setLocale($code);
        }
        

        return $this->contact();
    }
    
    /**
     * Handle pricing page with language parameter
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function pricingWithLang($code)
    {

        if ($code && isAvailableLang($code)) {
            session()->put('langCode', $code);
            app()->setLocale($code);
        }
        

        return $this->pricing();
    }	
}
