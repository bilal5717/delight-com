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

use App\Helpers\ArrayHelper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Traits\Sluggable\PageBySlug;
use App\Models\Post;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\ShippingAddress;
use App\Models\HomeSection;
use App\Models\SubAdmin1;
use App\Models\City;
use App\Models\SliderImage;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\PostDuration;
class HomeController extends FrontController
{
    use PageBySlug;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = [];
        $countryCode = config('country.code');
        $userAgent = request()->header('User-Agent');
        $queryView = request()->get('view'); // Query parameter for testing

        // Detect mobile or tablet devices
        $isMobile = preg_match('/mobile|android|phone|iphone|ipod/i', strtolower($userAgent));
        $isTablet = preg_match('/tablet|ipad|playbook|silk/i', strtolower($userAgent));
        $isMicroDevice = ($isMobile || $isTablet) && (\Request::path() == '/');


        // Handle micro device view based on session or detection
        if ($isMicroDevice) {
            Cookie::queue('simplepage', 'yes');
            return $this->microDevice();
        } else {
            Cookie::forget('simplepage');
            // cookie("simplepage",null);
        }

        // Handle desktop view
        Cookie::forget('simplepage');

        // Initialize DeviceDetector
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        if ($dd->isBot()) {
            return response()->json(['message' => 'Bot detected'], 200);
        }

        // Get homepage sections
        $cacheId = $countryCode . '.homeSections';
        $data['sections'] = Cache::remember($cacheId, $this->cacheExpiration, function () use ($countryCode) {
            $sections = collect();

            if (config('plugins.domainmapping.installed')) {
                try {
                    $sections = \extras\plugins\domainmapping\app\Models\DomainHomeSection::where('country_code', $countryCode)->orderBy('lft')->get();
                } catch (\Exception $e) {
                    // Handle exception
                }
            }

            if ($sections->count() <= 0) {
                $sections = HomeSection::orderBy('lft')->get();
            }

            return $sections;
        });

        $searchFormOptions = [];
        foreach ($data['sections'] as $section) {
            $method = str_replace(strtolower($countryCode) . '_', '', $section->method);

            if (method_exists($this, $method)) {
                try {
                    if (isset($section->value)) {
                        $this->{$method}($section->value);
                    } else {
                        $this->{$method}();
                    }

                    if ($method === 'getSearchForm') {
                        $searchFormOptions = $section->value;
                    }
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                }
            }
        }

        // Set SEO
        $this->setSeo($searchFormOptions);
        // Set default session view for devices
        if ($isMobile || $isTablet) {
            if (!session()->has('view')) {
                return view('pages.micro-device');
            }
        }

        return $this->showMainPage($data);
    }

    public function showMainPage($data)
    {
        return view('home.index', $data);
    }

    public function toggleView(Request $request)
    {
        $view = $request->input('view');
        if ($view === 'on') {
            session(['view' => 'simple']);

        } elseif ($view === null) {
            session(['view' => 'main']);
        }
        return redirect()->back();
    }


    /**
     * Get search form (Always in Top)
     *
     * @param array $value
     */
    protected function getSearchForm($value = [])
    {
        view()->share('searchFormOptions', $value);
    }

    protected function getMonthlyImages(array $value = [])
    {
        view()->share('monthlyImages', $value);
    }

    /**
     * Get locations & SVG map
     *
     * @param array $value
     */
    protected function getLocations($value = [])
    {
        // Get the default Max. Items
        $maxItems = 14;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($value);

        // Modal - States Collection
        $cacheId = config('country.code') . '.home.getLocations.modalAdmins';
        $modalAdmins = Cache::remember($cacheId, $cacheExpiration, function () {
            return SubAdmin1::currentCountry()->orderBy('name')->get(['code', 'name'])->keyBy('code');
        });
        view()->share('modalAdmins', $modalAdmins);

        // Get cities
        if (config('settings.listing.count_cities_posts')) {
            $cacheId = config('country.code') . 'home.getLocations.cities.withCountPosts';
            $cities = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
                return City::currentCountry()->withCount('posts')->take($maxItems)->orderByDesc('population')->orderBy('name')->get();
            });
        } else {
            $cacheId = config('country.code') . 'home.getLocations.cities';
            $cities = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
                return City::currentCountry()->take($maxItems)->orderByDesc('population')->orderBy('name')->get();
            });
        }
        $cities = collect($cities)->push(ArrayHelper::toObject([
            'id' => 0,
            'name' => t('More cities') . ' &raquo;',
            'subadmin1_code' => 0,
        ]));

        // Get cities number of columns
        $numberOfCols = 4;
        if (file_exists(config('larapen.core.maps.path') . strtolower(config('country.code')) . '.svg')) {
            if (isset($value['show_map']) && $value['show_map'] == '1') {
                $numberOfCols = (isset($value['items_cols']) && !empty($value['items_cols'])) ? (int)$value['items_cols'] : 3;
            }
        }

        // Chunk
        $maxRowsPerCol = round($cities->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
        $maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1;  // Fix array_chunk with 0
        $cities = $cities->chunk($maxRowsPerCol);

        view()->share('cities', $cities);
        view()->share('citiesOptions', $value);
    }

    /**
     * Get sponsored posts
     *
     * @param array $value
     */
    protected function getSponsoredPosts($value = [])
    {
        $type = 'sponsored';

        // Get the default Max. Items
        $maxItems = 20;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        $physicalProductItems = 4;
        if (isset($value['physical_product_items'])) {
            $physicalProductItems = (int)$value['physical_product_items'];
        }

        $serviceProductItems = 4;
        if (isset($value['service_product_items'])) {
            $serviceProductItems = (int)$value['service_product_items'];
        }

        $digitalProductItems = 4;
        if (isset($value['digital_product_items'])) {
            $digitalProductItems = (int)$value['digital_product_items'];
        }

        // Get the default orderBy value
        $orderBy = 'random';
        if (isset($value['order_by'])) {
            $orderBy = $value['order_by'];
        }

        // Get the default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($value);

        // Get featured posts
        $cacheId = config('country.code') . '.home.getPosts.' . $type;
        $posts = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems, $type, $orderBy, $serviceProductItems, $physicalProductItems, $digitalProductItems) {
            return Post::getLatestOrSponsored($maxItems, $type, $orderBy, $serviceProductItems, $physicalProductItems, $digitalProductItems);
        });

        $sponsored = null;
        if ($posts->count() > 0) {
            $sponsored = [
                'title' => t('Home - Sponsored Ads'),
                'link' => UrlGen::search(),
                'posts' => $posts,
            ];
            $sponsored = ArrayHelper::toObject($sponsored);
        }

        view()->share('featured', $sponsored);
        view()->share('featuredOptions', $value);
    }

    /**
     * Get latest posts
     *
     * @param array $value
     */
    protected function getLatestPosts($value = [])
    {
        $type = 'latest';

        // Get the default Max. Items
        $maxItems = 12;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }
        $physicalProductItems = 4;
        if (isset($value['physical_product_items'])) {
            $physicalProductItems = (int)$value['physical_product_items'];
        }

        $serviceProductItems = 4;
        if (isset($value['service_product_items'])) {
            $serviceProductItems = (int)$value['service_product_items'];
        }

        $digitalProductItems = 4;
        if (isset($value['digital_product_items'])) {
            $digitalProductItems = (int)$value['digital_product_items'];
        }

        // Get the default orderBy value
        $orderBy = 'date';
        if (isset($value['order_by'])) {
            $orderBy = $value['order_by'];
        }

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($value);

        // Get latest posts
        $cacheId = config('country.code') . '.home.getPosts.' . $type;
        $posts = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems, $type, $orderBy, $physicalProductItems, $serviceProductItems, $digitalProductItems) {
            return Post::getLatestOrSponsored($maxItems, $type, $orderBy, $serviceProductItems, $physicalProductItems, $digitalProductItems);
        });

        $latest = null;
        if (!empty($posts)) {
            $latest = [
                'title' => t('Home - Latest Ads'),
                'link' => UrlGen::search(),
                'posts' => $posts,
            ];
            $latest = ArrayHelper::toObject($latest);
        }

        view()->share('latest', $latest);
        view()->share('latestOptions', $value);
    }

    /**
     * Get list of categories
     *
     * @param array $value
     */
    protected function getCategories($value = [])
    {
        // Get the default Max. Items
        $maxItems = null;
        if (isset($value['max_items'])) {
            $maxItems = (int)$value['max_items'];
        }

        // Number of columns
        $numberOfCols = 3;

        // Get the Default Cache delay expiration
        $cacheExpiration = $this->getCacheExpirationTime($value);

        $cacheId = 'categories.parents.' . config('app.locale') . '.take.' . $maxItems;

        if (isset($value['type_of_display']) && in_array($value['type_of_display'], ['cc_normal_list', 'cc_normal_list_s'])) {

            $categories = Cache::remember($cacheId, $cacheExpiration, function () {
                $categories = Category::orderBy('lft')->get();

                return $categories;
            });
            $categories = collect($categories)->keyBy('id');
            $categories = $subCategories = $categories->groupBy('parent_id');

            if ($categories->has(null)) {
                if (!empty($maxItems)) {
                    $categories = $categories->get(null)->take($maxItems);
                } else {
                    $categories = $categories->get(null);
                }
                $subCategories = $subCategories->forget(null);

                $maxRowsPerCol = round($categories->count() / $numberOfCols, 0, PHP_ROUND_HALF_EVEN);
                $maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1;
                $categories = $categories->chunk($maxRowsPerCol);
            } else {
                $categories = collect();
                $subCategories = collect();
            }

            view()->share('categories', $categories);
            view()->share('subCategories', $subCategories);

        } else {

            $categories = Cache::remember($cacheId, $cacheExpiration, function () use ($maxItems) {
                if (!empty($maxItems)) {
                    $categories = Category::where(function ($query) {
                        $query->where('parent_id', 0)->orWhereNull('parent_id');
                    })->take($maxItems)->orderBy('lft')->get();
                } else {
                    $categories = Category::where(function ($query) {
                        $query->where('parent_id', 0)->orWhereNull('parent_id');
                    })->orderBy('lft')->get();
                }

                return $categories;
            });

            if (isset($value['type_of_display']) && $value['type_of_display'] == 'c_picture_icon') {
                $categories = collect($categories)->keyBy('id');
            } else {
                // $maxRowsPerCol = round($categories->count() / $numberOfCols, 0); // PHP_ROUND_HALF_EVEN
                $maxRowsPerCol = ceil($categories->count() / $numberOfCols);
                $maxRowsPerCol = ($maxRowsPerCol > 0) ? $maxRowsPerCol : 1; // Fix array_chunk with 0
                $categories = $categories->chunk($maxRowsPerCol);
            }

            view()->share('categories', $categories);

        }

        // Count Posts by category (if the option is enabled)
        $countPostsByCat = collect();
        if (config('settings.listing.count_categories_posts')) {
            $cacheId = config('country.code') . '.count.posts.by.cat.' . config('app.locale');
            $countPostsByCat = Cache::remember($cacheId, $cacheExpiration, function () {
                $countPostsByCat = Category::countPostsByCategory();

                return $countPostsByCat;
            });
        }
        view()->share('countPostsByCat', $countPostsByCat);

        // Export the Options
        view()->share('categoriesOptions', $value);
    }

    /**
     * Get mini stats data
     *
     * @param array $value
     */
    protected function getStats($value = [])
    {
        // Count posts
        $countPosts = Post::currentCountry()->unarchived()->count();

        // Count cities
        $countCities = City::currentCountry()->count();

        // Count users
        $countUsers = User::count();

        // Share vars
        view()->share('countPosts', $countPosts);
        view()->share('countCities', $countCities);
        view()->share('countUsers', $countUsers);

        // Export the Options
        view()->share('statsOptions', $value);
    }

    /**
     * Set SEO information
     *
     * @param array $searchFormOptions
     */
    protected function setSeo($searchFormOptions = [])
    {
        $title = getMetaTag('title', 'home');
        $description = getMetaTag('description', 'home');
        $keywords = getMetaTag('keywords', 'home');

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', strip_tags($description));
        MetaTag::set('keywords', $keywords);

        // Open Graph
        $this->og->title($title)->description($description);
        $backgroundImage = '';
        $isMonthlyImage = '';
        $monthlyImages = [];
        if (!empty(config('country.background_image'))) {
            if (isset($this->disk) && $this->disk->exists(config('country.background_image'))) {
                $backgroundImage = config('country.background_image');
            }
        }
        if (empty($backgroundImage)) {
            $getImage = SliderImage::where('month', date('F'))->first();
            if (!empty($getImage->url)) {
                $image_url = $getImage->url;
                if ($getImage->image_flag) {
                    $image_url = asset('storage/' . $getImage->url);
                }
                $monthlyImages['background_image'] = $image_url;
                $monthlyImages['height'] = $getImage->height;
                $isMonthlyImage = true;
            }
            $this->getMonthlyImages($monthlyImages);
        }
        if (empty($backgroundImage)) {
            if (isset($searchFormOptions['background_image']) && !empty($searchFormOptions['background_image'])) {
                $backgroundImage = $searchFormOptions['background_image'];
            }
        }
        if (!empty($backgroundImage)) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
            if ($isMonthlyImage) {
                $this->og->image($backgroundImage, [
                    'width' => 600,
                    'height' => 600,
                ]);
            } else {
                $this->og->image(imgUrl($backgroundImage, 'bgHeader'), [
                    'width' => 600,
                    'height' => 600,
                ]);
            }
        }
        view()->share('og', $this->og);
    }

    /**
     * @param array $value
     * @return int
     */
    private function getCacheExpirationTime($value = [])
    {
        // Get the default Cache Expiration Time
        $cacheExpiration = 0;
        if (isset($value['cache_expiration'])) {
            $cacheExpiration = (int)$value['cache_expiration'];
        }

        return $cacheExpiration;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function microDevice()
    {
        $slug = 'micro-device';
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
        $description = Str::limit(str_strip($page->content), 200);

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description);
        if (!empty($page->picture)) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
            $this->og->image(imgUrl($page->picture, 'bgHeader'), [
                'width' => 600,
                'height' => 600,
            ]);
        }
        view()->share('og', $this->og);

        return appView('pages.micro-device');
    }

    public function simpleHomePage()
    {
        $slug = 'simple-view';
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
        $description = Str::limit(str_strip($page->content), 200);

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description);
        if (!empty($page->picture)) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
            $this->og->image(imgUrl($page->picture, 'bgHeader'), [
                'width' => 600,
                'height' => 600,
            ]);
        }
        view()->share('og', $this->og);

        return appView('home.simple');
    }

    public function showCart()
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'Please login to view your cart.');
    }

    $userId = auth()->user()->id;
    $cartItems = Cart::with('post')->where('user_id', $userId)->get();
    $durationIds = $cartItems->pluck('duration_id')->unique()->filter()->values();
    $durations = PostDuration::whereIn('id', $durationIds)->get()->keyBy('id');
    return view('post.cart', compact('cartItems', 'durations'));
}

    public function removeCart($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        if (request()->ajax()){
            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart'
            ]);
        }

        return redirect()->route('carts')->with('success', 'Item removed from cart');
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:carts,id',
            'order' => 'required|boolean'
        ]);

        $cartItem = Cart::where('id', $request->item_id)
                      ->where('user_id', auth()->id())
                      ->firstOrFail();

        $cartItem->update(['order' => $request->order]);

        return response()->json([
            'success' => true,
            'message' => 'Selection updated',
            'selected_count' => Cart::where('user_id', auth()->id())
                                  ->where('order', true)
                                  ->count()
        ]);
    }

    public function resetOrderStatus(Request $request)
    {
        try {
            // Update all cart items to set order = 0
            Cart::where('user_id', auth()->id())->update(['order' => 0]);

            return response()->json([
                'success' => true,
                'message' => __('Order status reset successfully.')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('An error occurred while resetting order status.')
            ], 500);
        }
    }
}
