<?php
/**
 * 
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use Illuminate\Support\Facades\Cache;
use Torann\LaravelMetaTags\Facades\MetaTag;

class SitemapController extends FrontController
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		$data = [];
		
		// Get Categories
		$cacheId = 'categories.all.' . app()->getLocale();
		$cats = Cache::remember($cacheId, $this->cacheExpiration, function () {
			return Category::orderBy('lft')->get();
		});
		$cats = collect($cats)->keyBy('id');
		$cats = $subCats = $cats->groupBy('parent_id');
		
		if ($cats->has(null)) {
			$col = round($cats->get(null)->count() / 3, 0, PHP_ROUND_HALF_EVEN);
			$col = ($col > 0) ? $col : 1;
			$data['cats'] = $cats->get(null)->chunk($col);
			$data['subCats'] = $subCats->forget(null);
		} else {
			$data['cats'] = collect([]);
			$data['subCats'] = collect([]);
		}
		
		// Get Cities
		$limit = 100;
		$cacheId = config('country.code') . '.cities.take.' . $limit;
		$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($limit) {
			return City::currentCountry()->take($limit)->orderBy('population', 'DESC')->orderBy('name')->get();
		});
		
		$col = round($cities->count() / 4, 0, PHP_ROUND_HALF_EVEN);
		$col = ($col > 0) ? $col : 1;
		$data['cities'] = $cities->chunk($col);

		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'sitemap'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'sitemap')));
		MetaTag::set('keywords', getMetaTag('keywords', 'sitemap'));
		
		return appView('sitemap.index', $data);
	}

    /**
     * Handle sitemap with language parameter
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function indexWithLang($code)
    {
        if ($code && isAvailableLang($code)) {
            app()->setLocale($code); // this MUST happen before any other logic
            session()->put('langCode', $code);
        }

        // Now forward to index (which will use correct locale)
        return $this->index();
    }
}
