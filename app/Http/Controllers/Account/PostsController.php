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

namespace App\Http\Controllers\Account;

use App\Helpers\ArrayHelper;
use App\Helpers\Date;
use App\Helpers\Search\PostQueries;
use App\Helpers\UrlGen;
use App\Http\Controllers\Post\CreateOrEdit\Traits\PricingTrait;
use App\Http\Controllers\Post\CreateOrEdit\Traits\RetrievePaymentTrait;
use App\Http\Controllers\Search\Traits\LocationTrait;
use App\Http\Requests\PackageRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\Package;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\Addon;
use App\Models\PostAddon;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Notifications\PostArchived;
use App\Notifications\PostDeleted;
use App\Notifications\PostRepublished;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Payment as PaymentHelper;
use App\Http\Controllers\Post\CreateOrEdit\Traits\MakeBulkPaymentTrait;
use App\Models\ServiceSettings;

class PostsController extends AccountBaseController
{
	use LocationTrait, PricingTrait, MakeBulkPaymentTrait, RetrievePaymentTrait;

	public $request;
	public $data;
	public $msg = [];
	public $uri = [];
	public $packages;
	public $paymentMethods;

	private $perPage = 12;

	public function __construct()
	{
		parent::__construct();

		$this->perPage = (is_numeric(config('settings.listing.items_per_page'))) ? config('settings.listing.items_per_page') : $this->perPage;

		$this->middleware(function ($request, $next) {
			$this->request = $request;
			$this->commonQueries();

			return $next($request);
		});
	}

	/**
	 * @param $pagePath
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function getPage($pagePath)
	{
		view()->share('pagePath', $pagePath);

		switch ($pagePath) {
			case 'my-posts':
				return $this->getMyPosts();
				break;
			case 'archived':
				return $this->getArchivedPosts($pagePath);
				break;
			case 'favourite':
				return $this->getFavouritePosts();
				break;
			case 'pending-approval':
				return $this->getPendingApprovalPosts();
				break;
			default:
				abort(404);
		}
	}

	/**
	 * @param null $postId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function getMyPosts($postId = null)
	{
		$pagePath = 'my-posts';

		// If "offline" button is clicked
		if (Str::contains(url()->current(), $pagePath . '/' . $postId . '/offline')) {
			$post = null;
			if (is_numeric($postId) && $postId > 0) {
				$post = Post::where('user_id', auth()->user()->id)->where('id', $postId)->first();
				if (empty($post)) {
					abort(404, t('Post not found'));
				}

				if ($post->archived != 1) {
					$post->archived = 1;
					$post->archived_at = Carbon::now(Date::getAppTimeZone());
					$post->archived_manually = 1;
					$post->save();

					if ($post->archived == 1) {
						$archivedPostsExpiration = config('settings.cron.manually_archived_posts_expiration', 180);

						$message = t('offline_putting_message', [
							'postTitle' => $post->title,
							'dateDel'   => Date::format($post->archived_at->addDays($archivedPostsExpiration)),
						]);

						flash($message)->success();

						// Send Confirmation Email or SMS
						if (config('settings.mail.confirmation') == 1) {
							try {
								// $post->notify(new PostArchived($post, $archivedPostsExpiration));
								Notification::route('mail', $post->user->primary_email ? $post->user->primary_email : $post->user->email)->notify(new PostArchived($post, $archivedPostsExpiration));
							} catch (\Exception $e) {
								flash($e->getMessage())->error();
							}
						}
					} else {
						flash(t("The putting offline has failed"))->error();
					}
				} else {
					flash(t("The ad is already offline"))->error();
				}
			} else {
				flash(t("The putting offline has failed"))->error();
			}

			return back();
		}

        $activeSettings = ServiceSettings::where('active', 1)->get();
        $serviceSettings = [];

        foreach ($activeSettings as $setting) {
            $serviceSettings[$setting->setting_key] = $setting->getFallbackValue('setting_value');
        }
		$data = [];
		$data['posts'] = $this->myPosts->paginate($this->perPage);

		// Meta Tags
		MetaTag::set('title', t('my_ads'));
		MetaTag::set('description', t('my_ads_on', ['appName' => config('settings.app.app_name')]));

		view()->share('pagePath', $pagePath);
        view()->share('account.posts', $serviceSettings);

		return appView('account.posts', $data);
	}

	/**
	 * @param null $postId
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
	 */
	public function getArchivedPosts($postId = null)
	{
		$pagePath = 'archived';

		// If "repost" button is clicked
		if (Str::contains(url()->current(), $pagePath . '/' . $postId . '/repost')) {
			$post = null;
			if (is_numeric($postId) && $postId > 0) {
				$post = Post::where('user_id', auth()->user()->id)->where('id', $postId)->first();
				if (empty($post)) {
					abort(404, t('Post not found'));
				}

				$postUrl = UrlGen::post($post);

				if ($post->archived != 0) {
					$post->archived = 0;
					$post->archived_at = null;
					$post->deletion_mail_sent_at = null;
					if ($post->archived_manually != 1) {
						$post->created_at = Carbon::now(Date::getAppTimeZone());
						$post->archived_manually = 0;
					}
					$post->save();

					if ($post->archived == 0) {
						flash(t("the_repost_has_done_successfully"))->success();

						// Send Confirmation Email or SMS
						if (config('settings.mail.confirmation') == 1) {
							try {
								Notification::route('mail', $post->user->primary_email ? $post->user->primary_email : $post->user->email)->notify(new PostRepublished($post));
								// $post->notify(new PostRepublished($post));
							} catch (\Exception $e) {
								flash($e->getMessage())->error();
							}
						}
					} else {
						flash(t("the_repost_has_failed"))->error();
					}
				} else {
					flash(t("The ad is already online"))->error();
				}

				return redirect($postUrl);
			} else {
				flash(t("the_repost_has_failed"))->error();
			}

			return redirect('account/' . $pagePath);
		}

		$data = [];
		$data['posts'] = $this->archivedPosts->paginate($this->perPage);

		// Meta Tags
		MetaTag::set('title', t('my_archived_ads'));
		MetaTag::set('description', t('my_archived_ads_on', ['appName' => config('settings.app.app_name')]));

		view()->share('pagePath', $pagePath);

		return appView('account.posts', $data);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getFavouritePosts()
	{
		$data = [];
		$data['posts'] = $this->favouritePosts->paginate($this->perPage);

		// Meta Tags
		MetaTag::set('title', t('my_favourite_ads'));
		MetaTag::set('description', t('my_favourite_ads_on', ['appName' => config('settings.app.app_name')]));

		return appView('account.posts', $data);
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getPendingApprovalPosts()
	{
		$data = [];
		$data['posts'] = $this->pendingPosts->paginate($this->perPage);

		// Meta Tags
		MetaTag::set('title', t('my_pending_approval_ads'));
		MetaTag::set('description', t('my_pending_approval_ads_on', ['appName' => config('settings.app.app_name')]));

		return appView('account.posts', $data);
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function getSavedSearch(HttpRequest $request)
	{
		$data = [];

		// Get QueryString
		$tmp = parse_url(url(request()->getRequestUri()));
		$queryString = (isset($tmp['query']) ? $tmp['query'] : 'false');
		$queryString = preg_replace('|\&pag[^=]*=[0-9]*|i', '', $queryString);

		// CATEGORIES COLLECTION
		$cats = Category::orderBy('lft')->get();
		$cats = collect($cats)->keyBy('id');
		view()->share('cats', $cats);

		// Search
		$savedSearch = SavedSearch::currentCountry()
			->where('user_id', auth()->user()->id)
			->orderBy('created_at', 'DESC')
			->simplePaginate($this->perPage, ['*'], 'pag');

		if (collect($savedSearch->getCollection())
			->keyBy('query')
			->keys()
			->contains(function ($value, $key) use ($queryString) {
				$qs1 = preg_replace('/(\s|%20)/ui', '+', $queryString);
				$qs2 = preg_replace('/(\s|\+)/ui', '%20', $queryString);
				$qs3 = preg_replace('/(\+|%20)/ui', ' ', $queryString);

				return ($value == $qs1 || $value == $qs2 || $value == $qs3);
			})
		) {

			parse_str($queryString, $queryArray);

			// QueryString vars
			$cityId = isset($queryArray['l']) ? $queryArray['l'] : null;
			$location = isset($queryArray['location']) ? $queryArray['location'] : null;
			$adminName = (isset($queryArray['r']) && !isset($queryArray['l'])) ? $queryArray['r'] : null;

			// Search
			if ($savedSearch->getCollection()->count() > 0) {
				// Pre-Search
				$preSearch = [
					'city'  => $this->getCity($cityId, $location),
					'admin' => $this->getAdmin($adminName),
				];

				// Search
				$data = (new PostQueries($preSearch))->fetch();
			}
		}
		$data['savedSearch'] = $savedSearch;

		// Meta Tags
		MetaTag::set('title', t('my_saved_search'));
		MetaTag::set('description', t('my_saved_search_on', ['appName' => config('settings.app.app_name')]));

		view()->share('pagePath', 'saved-search');

		return appView('account.saved-search', $data);
	}

	/**
	 * @param $pagePath
	 * @param null $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function destroy($pagePath, $id = null)
	{
		// Get Entries ID
		$ids = [];
		if (request()->filled('entries')) {
			$ids = request()->input('entries');
		} else {
			if (!is_numeric($id) && $id <= 0) {
				$ids = [];
			} else {
				$ids[] = $id;
			}
		}

		// Delete
		$nb = 0;
		if ($pagePath == 'favourite') {
			$savedPosts = SavedPost::where('user_id', auth()->user()->id)->whereIn('post_id', $ids);
			if ($savedPosts->count() > 0) {
				$nb = $savedPosts->delete();
			}
		} else if ($pagePath == 'saved-search') {
			$nb = SavedSearch::destroy($ids);
		} else {
			foreach ($ids as $item) {
				$post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
					->where('user_id', auth()->user()->id)
					->where('id', $item)
					->first();
				if (!empty($post)) {
					$tmpPost = ArrayHelper::toObject($post->toArray());

					// Delete Entry
					$nb = $post->delete();

					// Send an Email confirmation
					if (!empty($tmpPost->email)) {
						if (config('settings.mail.confirmation') == 1) {
							try {
								Notification::route('mail', $post->user->primary_email ? $post->user->primary_email : $post->user->email)->notify(new PostDeleted($tmpPost));
								// Notification::route('mail', $tmpPost->email)->notify(new PostDeleted($tmpPost));
							} catch (\Exception $e) {
								flash($e->getMessage())->error();
							}
						}
					}
				}
			}
		}

		// Confirmation
		if ($nb == 0) {
			flash(t("no_deletion_is_done"))->error();
		} else {
			$count = count($ids);
			if ($count > 1) {
				$message = t("x entities has been deleted successfully", ['entities' => t('ads'), 'count' => $count]);
			} else {
				$message = t("1 entity has been deleted successfully", ['entity' => t('ad')]);
			}
			flash($message)->success();
		}

		return redirect('account/' . $pagePath);
	}

	public function commonQueries()
	{
		// Messages
		if (request()->segment(2) == 'create') {
			$this->msg['post']['success'] = t("your_ad_has_been_created");
		} else {
			$this->msg['post']['success'] = t("your_ad_has_been_updated");
		}
		$this->msg['checkout']['success'] = t("We have received your payment");
		$this->msg['checkout']['cancel'] = t("payment_cancelled_text");
		$this->msg['checkout']['error'] = t("payment_error_text");

		// Set URLs
		if (request()->segment(2) == 'create') {
			$this->uri['previousUrl'] = 'my-posts/#entryId/payment';
			$this->uri['nextUrl'] = 'my-posts/#entryId/finish';
			$this->uri['paymentCancelUrl'] = url('account/my-posts/#entryId/payment/cancel');
			$this->uri['paymentReturnUrl'] = url('account/my-posts/#entryId/payment/success');
		} else {
			$this->uri['previousUrl'] = 'account/my-posts/#entryId/payment';
			// $this->uri['nextUrl'] = str_replace(['{#entryId}'], ['{#entryId}'], (config('routes.my-posts') ?? 'account/my-posts/{#entryId}'));
            $this->uri['nextUrl'] = str_replace(['{id}'], request()->ids, (config('routes.my-posts') ?? 'account/my-posts'));

            $this->uri['paymentCancelUrl'] = url('account/my-posts/#entryId/payment/cancel');
			$this->uri['paymentReturnUrl'] = url('account/my-posts/#entryId/payment/success');
		}

		// Payment Helper init.
		PaymentHelper::$country = collect(config('country'));
		PaymentHelper::$lang = collect(config('lang'));
		PaymentHelper::$msg = $this->msg;
		PaymentHelper::$uri = $this->uri;

		// Selected Package
		$package = $this->getSelectedPackage();
		view()->share('selectedPackage', $package);

		// Get Packages
		$this->packages = Package::applyCurrency()->with('currency')->orderBy('lft')->get();
		view()->share('packages', $this->packages);
		view()->share('countPackages', $this->packages->count());

		// Keep the Post's creation message
		// session()->keep(['message']);
		if (request()->segment(2) == 'create') {
			if (session()->has('tmpPostId')) {
				session()->flash('message', t('your_ad_has_been_created'));
			}
		}
	}


	public function upgradePosts($ids)
	{
		$id =  explode(',', $ids);
		$postData = Post::find($id);
		$this->packages = Package::applyCurrency()->with('currency')->orderBy('lft')->get();
		view()->share('packages', $this->packages);
		view()->share('countPackages', $this->packages->count());
		// Check if the form type is 'Single Step Form', and make redirection to it (permanently).
		// if (config('settings.single.publication_form_type') == '2') {
		// 	return redirect(url('edit/' . implode(",",$id)), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
		// }

		$data = [];

		// Get Post
		if (request()->segment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::currentCountry()->with([
				'latestPayment' => function ($builder) {
					$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
				},
			])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', session('tmpPostId'))
				->where('tmp_token', $id)
				->first();
		} else {
			$post = Post::currentCountry()->with([
				'latestPayment' => function ($builder) {
					$builder->with(['package'])->withoutGlobalScope(StrictActiveScope::class);
				},
			])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $id)
				->first();
		}

		if (empty($post)) {
			abort(404);
		}

		view()->share('post', $post);
		view()->share('postData', $postData);

		// Share the Post's Latest Payment Info (If exists)
		$this->sharePostLatestPaymentInfo($post);

		// Meta Tags
		if (request()->segment(2) == 'create') {
			MetaTag::set('title', getMetaTag('title', 'create'));
			MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
			MetaTag::set('keywords', getMetaTag('keywords', 'create'));
		} else {
			MetaTag::set('title', t('update_my_ad'));
			MetaTag::set('description', t('update_my_ad'));
		}
		// return appView('post.createOrEdit.multiSteps.packages', $data);

		return appView('account.upgrade-post', $data);
	}

	/**
	 * Store a new ad post.
	 *
	 * @param $postIdOrToken
	 * @param PackageRequest $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */

	public function postForm($postIdOrToken, PackageRequest $request)
	{
		// Get Post
		if (request()->segment(2) == 'create') {
			if (!session()->has('tmpPostId')) {
				return redirect('posts/create');
			}
			$post = Post::currentCountry()->with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('id', $request->ids)
				->where('tmp_token', $postIdOrToken)
				->first();
		} else {
			$post = Post::currentCountry()->with(['latestPayment'])
				->withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])
				->where('user_id', auth()->user()->id)
				->where('id', $postIdOrToken)
				->first();
		}

		if (empty($post)) {
			abort(404);
		}

		// MAKE A PAYMENT (IF NEEDED)

		// Check if the selected Package has been already paid for this Post
		$alreadyPaidPackage = false;
		if (!empty($post->latestPayment)) {
			if ($post->latestPayment->package_id == $request->input('package_id')) {
				$alreadyPaidPackage = true;
			}
		}

		// Check if Payment is required
		$package = Package::find($request->input('package_id'));
		if (!empty($package)) {
			if ($package->price > 0 && $request->filled('payment_method_id') && !$alreadyPaidPackage) {
				// Send the Payment
				return $this->sendPayment($request, $post);
			}
		}

		// IF NO PAYMENT IS MADE (CONTINUE)

		// Get the next URL
		if (request()->segment(2) == 'create') {
			$request->session()->flash('message', t('your_ad_has_been_created'));
			$nextStepUrl = 'posts/create/' . $postIdOrToken . '/finish';
		} else {
			flash(t('your_ad_has_been_updated'))->success();
			$nextStepUrl = UrlGen::postUri($post);
		}

		// Redirect
		return redirect($nextStepUrl);
	}
	public function addons($postId)
{
    // Fetch the post
    $post = Post::find($postId);
    $countPaymentMethods = count($this->paymentMethods);
    $countPackages = count($this->packages);
$service_type = $post->service_type;
    // Fetch post-specific add-ons
    $postAddons = PostAddon::where('post_id', $postId)->get();

    // Fetch all default add-ons from the database
    $defaultAddons = Addon::where('service_type', $service_type)
	->where('status' , 'active')
	->get();

    return view('post.addons', compact('post', 'countPaymentMethods', 'countPackages', 'postAddons', 'defaultAddons'));
}

	


	
/**
     * Delete the specified post addon.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
	public function deleteAddon($id)
{
    // Handle post-specific add-on deletion
    $postAddon = PostAddon::find($id); // Find the add-on in the `post_addons` table
    if ($postAddon) {
        $postAddon->delete(); // Delete the add-on from the database
        return response()->json(['success' => true, 'message' => 'Addon deleted successfully.']);
    }


    return response()->json(['success' => false, 'message' => 'Addon not found.'], 404);
}
}
