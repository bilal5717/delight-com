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

namespace App\Http\Controllers;

// Increase the server resources
$iniConfigFile = __DIR__ . '/../../Helpers/Functions/ini.php';
if (file_exists($iniConfigFile)) {
	include_once $iniConfigFile;
}

use App\Helpers\ArrayHelper;
use App\Helpers\Date;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\UrlGen;
use App\Models\Category;
use App\Models\Company;
use App\Models\Page;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\City;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Watson\Sitemap\Facades\Sitemap;

class SitemapsController extends FrontController
{
	protected $defaultDate = '2015-10-30T20:10:00+02:00';
	
	/**
	 * SitemapsController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->commonQueries();
			
			return $next($request);
		});
	}
	
	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		// Set the Country's Locale & Default Date
		$this->applyCountrySettings();
	}
	
	/**
	 * Index Sitemap
	 *
	 * @return mixed
	 */
	public function index()
	{
		foreach ($this->countries as $item) {
			// Get Country Settings
			$country = $this->getCountrySettings($item->get('code'), false);
			if (empty($country)) {
				continue;
			}
			
			Sitemap::addSitemap(dmUrl($country, $country->icode . '/sitemaps.xml'));
		}
		
		return Sitemap::index();
	}
	
	/**
	 * Index Single Country Sitemap
	 *
	 * @param null $countryCode
	 * @return bool
	 */
	public function site($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		Sitemap::addSitemap(dmUrl(collect($country), $country->icode . '/sitemaps/pages.xml'));
		Sitemap::addSitemap(dmUrl(collect($country), $country->icode . '/sitemaps/categories.xml'));
		Sitemap::addSitemap(dmUrl(collect($country), $country->icode . '/sitemaps/cities.xml'));

		$countPosts = Post::verified()->countryOf($country->code)->count();
		if ($countPosts > 0) {
			Sitemap::addSitemap(dmUrl(collect($country), $country->icode . '/sitemaps/posts.xml'));
		}
        Sitemap::addSitemap(dmUrl(collect($country), $country->icode . '/sitemaps/companies.xml'));
		
		return Sitemap::index();
	}
	
	/**
	 * @param null $countryCode
	 * @return bool
	 */
	public function pages($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
        $languages = config('sitemap-languages.supported');
        $defaultLang = config('sitemap-languages.default');
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$queryString = '';
		if (!config('plugins.domainmapping.installed')) {
			$queryString = '?d=' . $country->code;
		}

        $homeSitemapUrls = [
            url('/'),
            UrlGen::sitemap($country->icode)
        ];

        foreach ($homeSitemapUrls as $baseUrl) {
            // Add default (non-lang-prefixed) URL first
            Sitemap::addTag($baseUrl, $this->defaultDate, 'daily', '0.7');

            // Add language-specific versions
            foreach ($languages as $lang) {
                if ($lang === $defaultLang) {
                    continue;
                }

                $localizedUrl = $baseUrl . '/lang/' . $lang;
                Sitemap::addTag($localizedUrl, $this->defaultDate, 'weekly', '0.7');
            }
        }
		
		$url = UrlGen::search([], [], false, $country->icode) . $queryString;
		Sitemap::addTag($url, $this->defaultDate, 'daily', '0.6');
		
		$pages = Cache::remember('pages.' . $country->locale, $this->cacheExpiration, function () use ($country) {
			$pages = Page::orderBy('lft', 'ASC')->get();
			
			return $pages;
		});

        if ($pages->count() > 0) {
            foreach ($pages as $page) {
                // 1. Add default (non-lang-prefixed) URL FIRST
                $defaultUrl = UrlGen::page($page, $country->icode);
                Sitemap::addTag($defaultUrl, $this->defaultDate, 'weekly', '0.7');

                // 2. Then add all translated versions
                foreach ($languages as $lang) {
                    if ($lang === $defaultLang) {
                        continue; // skip default, already added
                    }

                    // Append /lang/{locale} to the base URL
                    $localizedUrl = $defaultUrl . '/lang/' . $lang;
                    Sitemap::addTag($localizedUrl, $this->defaultDate, 'weekly', '0.7');
                }
            }
        }

        $staticUrls = [
            UrlGen::contact(),
            UrlGen::login(),
            UrlGen::logout(),
            UrlGen::register(),
            UrlGen::passwordReset(),
            UrlGen::pricing(),
            UrlGen::addPost(),
        ];

        foreach ($staticUrls as $baseUrl) {
            // Add default (non-lang-prefixed) URL first
            Sitemap::addTag($baseUrl, $this->defaultDate, 'daily', '0.7');

            // Add language-specific versions
            foreach ($languages as $lang) {
                if ($lang === $defaultLang) {
                    continue;
                }

                $localizedUrl = $baseUrl . '/lang/' . $lang;
                Sitemap::addTag($localizedUrl, $this->defaultDate, 'daily', '0.7');
            }
        }

        return Sitemap::render();

	}
	
	/**
	 * @param null $countryCode
	 * @return bool
	 */
	public function categories($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		// Categories
		$cacheId = 'categories.' . $country->locale . '.all';
		$cats = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country) {
			$cats = Category::orderBy('lft')->get();
			
			return $cats;
		});
		
		if ($cats->count() > 0) {
			$cats = collect($cats)->keyBy('id');
			
			foreach ($cats as $cat) {
				$url = UrlGen::category($cat, $country->icode);
				Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.8');
			}
		}
		
		return Sitemap::render();
	}
	
	/**
	 * @param null $countryCode
	 * @return bool
	 */
	public function cities($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$limit = (int)env('XML_SITEMAP_LIMIT', 1000);
		$cacheId = $country->icode . '.cities.take.' . $limit;
		$cities = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
			return City::countryOf($country->code)->take($limit)->orderBy('population', 'DESC')->orderBy('name')->get();
		});
		
		if ($cities->count() > 0) {
			foreach ($cities as $city) {
				$city->name = trim(head(explode('/', $city->name)));
				$url = UrlGen::city($city, $country->icode);
				Sitemap::addTag($url, $this->defaultDate, 'weekly', '0.7');
			}
		}
		
		return Sitemap::render();
	}
	
	/**
	 * @param null $countryCode
	 * @return bool
	 */
	public function posts($countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = config('country.code');
		}
		
		// Get Country Settings
		$country = $this->getCountrySettings($countryCode);
		if (empty($country)) {
			return false;
		}
		
		$limit = (int)env('XML_SITEMAP_LIMIT', 1000);
		$cacheId = $country->icode . '.sitemaps.posts.xml';
		$posts = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country, $limit) {
			return Post::verified()->countryOf($country->code)->take($limit)->orderBy('created_at', 'DESC')->get();
		});
		
		if ($posts->count() > 0) {
			foreach ($posts as $post) {
				$url = UrlGen::post($post);
				Sitemap::addTag($url, $post->created_at, 'daily', '0.6');
			}
		}
		
		return Sitemap::render();
	}

    public function companies($countryCode = null)
    {
        if (empty($countryCode)) {
            $countryCode = config('country.code');
        }

        // Get Country Settings
        $country = $this->getCountrySettings($countryCode);
        if (empty($country)) {
            return false;
        }

        $limit = (int)env('XML_SITEMAP_LIMIT', 1000);
        $cacheId = $country->icode . '.sitemaps.companies.xml';
        $companies = Cache::remember($cacheId, $this->cacheExpiration, function () use ($country) {
            $companies = Company::orderBy('id')->get();

            return $companies;
        });

        if ($companies->count() > 0) {
            foreach ($companies as $company) {
                $url = route('company', ['slug' => $company->company_slug ?? $company->id, 'tab' => 'profile']);
                Sitemap::addTag($url, $company->created_at, 'weekly', '0.8');
            }
        }

        return Sitemap::render();
    }
	
	/**
	 * Set the Country's Locale & Default Date
	 *
	 * @param null $locale
	 * @param null $timeZone
	 */
	public function applyCountrySettings($locale = null, $timeZone = null)
	{
		// Set the App Language
		if (!empty($locale)) {
			App::setLocale($locale);
		} else {
			App::setLocale(config('app.locale'));
		}
		
		// Date: Carbon object
		$this->defaultDate = Carbon::parse(date('Y-m-d H:i'));
		if (!empty($timeZone)) {
			$this->defaultDate->timezone($timeZone);
		} else {
			$this->defaultDate->timezone(Date::getAppTimeZone());
		}
	}
	
	/**
	 * Get Country Settings
	 *
	 * @param $countryCode
	 * @param bool $canApplySettings
	 * @return array|null
	 */
	public function getCountrySettings($countryCode, $canApplySettings = true)
	{
		$tab = [];
		
		// Get Country Info
		$country = CountryLocalization::getCountryInfo($countryCode);
		if ($country->isEmpty()) {
			return null;
		}
		
		$tab['code'] = $country->get('code');
		$tab['icode'] = $country->get('icode');
		$tab['time_zone'] = ($country->has('time_zone')) ? $country->get('time_zone') : config('app.timezone');
		
		// Language
		if (!$country->get('lang')->isEmpty() && $country->get('lang')->has('abbr')) {
			$tab['locale'] = $country->get('lang')->get('abbr');
		} else {
			$tab['locale'] = config('app.locale');
		}
		
		$tab = ArrayHelper::toObject($tab);
		
		// Set the Country's Locale & Default Date
		if ($canApplySettings) {
			$this->applyCountrySettings($tab->locale, $tab->time_zone);
		}
		
		return $tab;
	}
}
