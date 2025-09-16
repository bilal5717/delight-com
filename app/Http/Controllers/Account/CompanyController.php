<?php

namespace App\Http\Controllers\Account;

use App\Helpers\ArrayHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CommonTrait;
use App\Models\Blacklist;
use App\Models\Category;
use App\Models\Company;
use App\Models\Post;
use App\Models\PostDuration;
use App\Models\PostType;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CompanyController extends Controller
{
    use CommonTrait;
	public $wordLimit = 500;

	public function __construct()
	{
		view()->share('pagePath', 'company-profile');
		view()->share('og', '');

        $this->loadPlugins();
	}
	public function index(Request $request)
	{
        $categories = Category::whereNull('parent_id')->orderby('name','ASC')->with('children')->get();

		if (Auth::user()) {
			$company = Auth::user()->company;
			view()->share('company', $company);

			$post = PostType::where('id','!=',1)->orderBy('lft')->get();
			view()->share('post', $post);

            // Get Blacklist Words Data
            $data['blacklistWords'] = Blacklist::withoutGlobalScopes()->select('entry')->orderBy('id')->pluck('entry')->toArray();
            view()->share('blacklistWords', $data['blacklistWords']);

			try {
				// Ajax response
				if ($request->ajax()) {
					$data = [];
					$data['initialPreview'] = [];
					$data['initialPreviewConfig'] = [];

					if (!empty($company->logo)) {
						// Get Deletion Url
						$initialPreviewConfigUrl = url('account/' . $company->id . '/logo/delete');

						// Build Bootstrap-Input plugin's parameters
						$data['initialPreview'][] = imgUrl($company->logo, 'company');

						$data['initialPreviewConfig'][] = [
							'caption' => last(explode(DIRECTORY_SEPARATOR, $company->logo)),
							'size'    => (isset($this->disk) && $this->disk->exists($company->logo)) ? (int)$this->disk->size($company->logo) : 0,
							'url'     => $initialPreviewConfigUrl,
							'key'     => $company->id,
							'extra'   => ['id' => $company->id],
						];
					}

					return response()->json($data);
				}
			} catch (\Exception $e) {
				dd($e);
			}
		}
		return appView('account.company-profile')->with('categories', $categories)->with('company', $company);
	}

	public function viewCompanyDetails(Request $request)
{
    $activeTab = $request->tab;
    $categories = Category::where('active', 1)->orderby('name','ASC')->get();
    
    if(!ctype_digit($request->slug)) {
        $company = Company::with('companyAddresss', 'defaultCompanyAddresss', 'user')->where('company_slug', $request->slug)->first();
    } else {
        $company = Company::with('companyAddresss', 'defaultCompanyAddresss', 'user')->find($request->slug);
    }

    view()->share('company', $company);

    // Handle different tabs
    if ($activeTab === 'availability') {
        // Get posts that are active, require booking, and have durations
        $posts = Post::where('user_id', $company->user_id)
                    ->where('booking_required', 1)
                    ->whereHas('durations', function($query) {
                        $query->where('is_active', 1);
                    })
                    ->with(['durations' => function($query) {
                        $query->where('is_active', 1);
                    }])
                    ->get();
        $postData = [
            'posts' => $posts,
            'count' => $posts->count(),
            'activeTab' => $activeTab,
        ];
    } else {
        // For other tabs, use cached vendor ads
        $cacheId = 'posts.similar.company.' . $company->id . '.user.' . $company->user->id;
        $posts = Cache::remember($cacheId, 24, function () use ($company) {
            return (new Post())->getSimilarByVendor($company->user);
        });
        
        $postData = [
            'posts' => $posts,
            'count' => $posts->count(),
            'activeTab' => $activeTab,
        ];
    }

    // Featured Area Data
    $featured = [
        'title' => t('Vendor Ads'),
        'posts' => $posts,
    ];
    $data = (count($posts) > 0) ? ArrayHelper::toObject($featured) : null;

    // Random Ads
    $randomPosts = [];
    if(count($posts) == 0) {
        $cacheId = 'posts.similar.random.' . $company->id . '.user.' . $company->user->id;
        $randomPosts = Cache::remember($cacheId, 24, function () {
            return (new Post())->getSimilarByRandom();
        });
    }
    
    $random = [
        'title' => t('Random Ads'),
        'posts' => $randomPosts,
    ];
    $randomData = (count($randomPosts) > 0) ? ArrayHelper::toObject($random) : null;

    // Meta Tags
    if($company->companyAddresss->count() > 0) {
        MetaTag::set('title', "Welcome to the Official Online Shop of ".$company->name." in ".$company->companyAddresss->first()->city->name.", ".$company->companyAddresss->first()->city->country->name);
    }
    MetaTag::set('description', $company->description);
    MetaTag::set('keywords', str_replace(',', ', ', $company->keywords));

    $postTypes = PostType::where('id','!=',1)->orderBy('lft')->get()->toArray();

    return appView('account.company')
        ->with('postData', $postData)
        ->with('categories', $categories)
        ->with('company', $company)
        ->with('companyAddresss')
        ->with('companyAddresss.city')
        ->with("featured", $data)
        ->with("random", $randomData)
        ->with('postTypes', $postTypes);
}
	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name'                  => 'required',
			'description'           => 'required',
			'email'           => 'required',
			'facebook'           => '',
			'twitter'           => '',
			'instagram'           => '',
			'linkedin'           => '',
			'kvk'           => '',
			'wechat'           => '',
			'website'           => '',
			'phone'                 => 'required',
			'revenue'               => 'required',
			'category_id'               => 'required',
			'registration_number'   => 'required',
			'default_business_type'   => '',
		]);
		if ($validator->fails()) {
			return redirect('account/company-profile')
				->withErrors($validator)
				->withInput();
		}

		// Retrieve the validated input...
		$validated = $validator->validated();

		if ($request->hasFile('logo')) {
			$filename = $request->logo;
			$disk = Storage::disk('public')->put('company-logo', $filename);
			$validated['logo'] = $disk;
		}

		$value = trim(mb_strtolower($validated['description']));
		$words = Blacklist::whereIn('type', ['word', 'domain', 'email'])->get();

		if ($words->count() > 0) {
			foreach ($words as $word) {
                if($word->entry != ')))')
                {
                    // Check if a ban's word is contained in the user entry
                    $startPatten = '\s\-.,;:=/#\|_<>';
                    $endPatten = $startPatten . 's';
                    try {
                        if (preg_match('|[' . $startPatten . '\\\]+' . $word->entry . '[' . $endPatten . '\\\]+|ui', ' ' . $value . ' ')) {
                            return redirect()->route('account/company-profile')->withErrors([trans('company.can_not_use_bad_word_in_description', ['badword' => $word->entry])])->withInput();
                        }
                    } catch (\Exception $e) {
                        if (preg_match('|[' . $startPatten . ']+' . $word->entry . '[' . $endPatten . ']+|ui', ' ' . $value . ' ')) {
                            return redirect()->route('company-profile')->withErrors([trans('company.can_not_use_bad_word_in_description', ['badword' => $word->entry])])->withInput();
                        }
                    }
				}
			}
		}

		$desc = strip_tags($validated['description']);
		$desc_len = Str::of($desc)->wordCount();

		if ($desc_len >= $this->wordLimit) {
			return redirect()->route('company-profile')->withErrors([trans('company.company_description_length_not_greater_then')])->withInput();
		}

        $validated['company_slug'] = SlugService::createSlug(Company::class, 'company_slug', $request->name, ['unique' => true]);

		$validated['user_id'] = Auth::user()->id;
		$validated['keywords'] = generateKeywords($validated['name'] . ' ' . $desc);
		$data = DB::table('companies')
			->updateOrInsert(['user_id' => Auth::user()->id], $validated);
		return redirect('account/company-profile')->with('success', trans('company.company_profile_updated'));
	}
}