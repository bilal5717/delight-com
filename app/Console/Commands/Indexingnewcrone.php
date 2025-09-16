<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;
use App\Models\Category;
use Google;
use Google_Service_Indexing;
use Google_Service_Indexing_UrlNotification;
use App\Helpers\UrlGen;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Traits\GoogleIndexingTrait;
use App\Models\City;
use App\Models\IndexingHistory;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class Indexingnewcrone extends Command
{
    use GoogleIndexingTrait;
    
    protected $signature = 'indexing:newcrone';

    protected $description = 'Submit website URLs to search engines';

    public function handle()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle()
	{
		$this->indexCronjobPages();
		$this->indexCronjobCategories();
		$this->indexCronjobLocation();
		$this->indexCronjobPosts();
	}

	private function indexCronjobPosts()
	{
		$columns = ['id', 'country_code', 'category_id', 'post_type_id', 'title', 'city_id', 'keywords', 'summary', 'created_at'];
		$postTypes = [];
		if (config('settings.seo.index_private')) {
			array_push($postTypes, 1);
		} else if (config('settings.seo.index_professional')) {
			array_push($postTypes, 2);
		} else if (config('settings.seo.index_charity')) {
			array_push($postTypes, 3);
		} else if (config('settings.seo.index_government')) {
			array_push($postTypes, 4);
		}

		$newPosts = Post::select($columns)
			->verified()
			->unarchived()
			->whereIn("post_type_id", $postTypes)
			->whereBetween('updated_at', [Carbon::now()->subHours(12), Carbon::now()])
			->get();

		$postIds = (count($newPosts) !== 0) ? array_column((array)$newPosts->toArray(), 'id') : [];
		$highestCounter = IndexingHistory::select(['indexing_count'])->where("type","posts")->orderBy("indexing_count",'DESC')->first();

		$oldPosts = Post::select($columns)
			->verified()
			->unarchived()
			->has("indexingHistory", '<=', ($highestCounter ? $highestCounter->indexing_count - 1 : 0))
			->whereNotIn("id", $postIds)
			->whereIn("post_type_id", $postTypes)
			->orderBy('id', 'ASC')
			->limit(400)
			->get();

		$this->indexing($newPosts);
		$this->indexing($oldPosts);
	}

	private function indexCronjobCategories()
	{

		$newCategories = Category::whereBetween('updated_at', [Carbon::now()->subHours(12), Carbon::now()])->get();

		$categoryIds = (count($newCategories) !== 0) ? array_column((array)$newCategories->toArray(), 'id') : [];

		$oldCategories = Category::whereNotIn("id", $categoryIds)->orderBy('id', 'ASC')->limit(50)->get();

		$this->indexing($newCategories);
		$this->indexing($oldCategories);
	}

	private function indexCronjobPages()
	{

		$data = Page::whereBetween('updated_at', [Carbon::now()->subHours(12), Carbon::now()])->get();
		if (count($data) === 0) {
			$data = Page::orderBy('id', 'ASC')->limit(50)->get();
		}
		$this->indexing($data);
	}

	private function indexCronjobLocation()
	{

		$data = City::currentCountry()->orderByDesc('population')->orderBy('name')->limit(50)->get();
		$this->indexing($data);
	}

	private function indexing($posts)
	{

		if ($posts->count() > 0) {
			$urls = [];
			try {
				$googleClient = $this->setupGoogle();
				$googleClient->setUseBatch(true);

				$service = new Google_Service_Indexing($googleClient);
				$batch = $service->createBatch();

				$postBody = new Google_Service_Indexing_UrlNotification();

				foreach ($posts as $post) {
					$url = "";
					if ($post->getTable() == 'categories') {
						$url = url(UrlGen::category($post, null, null, $post->parent));
					} else if ($post->getTable() == 'pages') {
						$url = url(UrlGen::page($post));
					} else if ($post->getTable() == 'cities') {
						$url = url(UrlGen::city($post));
					} else {
						$url = url(UrlGen::postUri($post));
					}

					$postBody->setUrl($url);
					$postBody->setType('URL_UPDATED');
					$batch->add($service->urlNotifications->publish($postBody));
					$this->manageIndexingHistory($post, $url);
					array_push($urls, $url);
				}

				$results = $batch->execute();
				$this->bing_indexing($urls);
			} catch (\Exception $e) {
				dd($e->getMessage());
			}
		}
	}
	private function bing_indexing($urls)
	{

		$url = [];
		if (count($urls) > 0) {
			try {
				$apiKey = config('bing-indexing.api_credentials');

				$http = Http::post(
					"https://www.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=" . $apiKey,
					[
						"siteUrl" => url('/'),
						"urlList" => $urls
					]
				);
			} catch (\Exception $e) {
				dd($e->getMessage());
			}
		}
	}

	//Function to manage the history of indexing urls
	function manageIndexingHistory($data, $url)
	{
		$history = IndexingHistory::where("reference_id", $data->id)->where('type', $data->getTable())->first();

		if ($history) {
			$history->indexing_count = $history->indexing_count + 1;
			$history->update();
		} else {
			IndexingHistory::create([
				"url" => $url,
				"reference_id" => $data->id,
				"type" => $data->getTable(),
				"indexing_count" => 1,
			]);
		}
	}
	private function google_indexing_old($posts)
	{

		if ($posts->count() > 0) {
			try {
				$googleClient = $this->setupGoogle();
				$googleClient->setUseBatch(true);

				$service = new Google_Service_Indexing($googleClient);
				$batch = $service->createBatch();

				$postBody = new Google_Service_Indexing_UrlNotification();

				foreach ($posts as $post) {
					$url = "";
					if ($post->getTable() == 'categories') {
						$url = url(UrlGen::category($post, null, null, $post->parent));
					} else if ($post->getTable() == 'pages') {
						$url = url(UrlGen::page($post));
					} else {
						$url = url(UrlGen::postUri($post));
					}

					$postBody->setUrl($url);
					$postBody->setType('URL_UPDATED');
					$batch->add($service->urlNotifications->publish($postBody));
					$this->manageIndexingHistory($post, $url);
				}

				$results = $batch->execute();
			} catch (\Exception $e) {
				dd($e->getMessage());
			}
		}
	}

	private function bing_indexing_old($posts)
	{

		$url = [];
		if ($posts->count() > 0) {
			try {
				foreach ($posts as $post) {
					// $get_url = $post->getTable() == 'categories' ? url(UrlGen::category($post, null, null, $post->parent)) : url(UrlGen::postUri($post));

					$get_url = "";
					if ($post->getTable() == 'categories') {
						$get_url = url(UrlGen::category($post, null, null, $post->parent));
					} else if ($post->getTable() == 'pages') {
						$get_url = url(UrlGen::page($post));
					} else {
						$get_url = url(UrlGen::postUri($post));
					}

					array_push($url, $get_url);
				}
				$apiKey = config('bing-indexing.api_credentials');

				$http = Http::post(
					"https://www.bing.com/webmaster/api.svc/json/SubmitUrlbatch?apikey=" . $apiKey,
					[
						"siteUrl" => url('/'),
						"urlList" => $url
					]
				);
			} catch (\Exception $e) {
				dd($e->getMessage());
			}
		}
	}
}