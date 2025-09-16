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
use Illuminate\Support\Facades\DB;
use App\Models\IndexingHistory;

class IndexingCron extends Command
{
    use GoogleIndexingTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indexing:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add newly created or updated URLs to Google and Bing indexing.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
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
        $tenDaysAgo = Carbon::now()->subDays(10);
        $newPosts = Post::where("verified_email", 1)
            ->where("created_at", ">", $tenDaysAgo)
            ->where("google_status", 0)
            ->orWhere(function ($query) use ($tenDaysAgo) {
                $query->where("verified_email", 1)
                    ->where("updated_at", ">", $tenDaysAgo)
                    ->where("google_status", 0);
            })
            ->orderBy("created_at", "desc")
            ->get();

        $oldPosts = Post::where("verified_email", 1)
            ->where("created_at", "<=", $tenDaysAgo)
            ->where("updated_at", "<=", $tenDaysAgo)
            ->where("google_status", 0)
            ->orderBy("created_at", "asc")
            ->get();

        $updatedPosts = Post::where("verified_email", 1)
            ->where("updated_at", ">", $tenDaysAgo)
            ->where("google_status", 1)
            ->get();

        $posts = collect([]);

        // Prioritize new posts
        if ($newPosts->count() > 0) {
            $posts = $newPosts;
        } else if ($oldPosts->count() > 0) {
            $posts = $oldPosts;
        }

        if ($posts->count() > 0) {
            $this->indexedGoogleBing($posts);
        }

        if ($updatedPosts->count() > 0) {
            $this->indexedGoogleBing($updatedPosts);
        }

        $categories = Category::where("active", 1)
            ->where(function ($q) {
                $q->where("google_status", 0)
                    ->orWhere("bing_status", 0);
            })
            ->get();

        $this->indexedGoogleBing($categories);
    }

    private function indexedGoogleBing($values)
    {
        $googleRequests = 0; // Counter for Google API requests
        $bingRequests = 0; // Counter for Bing API requests

        if ($values->count() > 0) {
            foreach ($values as $value) {
                $url = $value->getTable() == 'categories' ? url(UrlGen::category($value, null, null, $value->parent)) : url(UrlGen::postUri($value));
                $now = Carbon::now();

                // check if post is new or updated within 10 days
                $recently_updated = $value->updated_at->diffInDays($now) <= 10;
                $is_new_post = $value->created_at->diffInDays($now) <= 10;

                // prioritize submitting new posts first
                if ($is_new_post) {
                    $indexing_type = 'new_post';
                } else if ($recently_updated) {
                    $indexing_type = 'updated_post';
                } else {
                    $indexing_type = 'old_post';
                }

                // submit to Google indexing
                if (
                    $value->google_status == false ||
                    ($value->google_status == true && $indexing_type == 'updated_post')
                ) {
                    if ($googleRequests >= 190) {
                        break; // Reached maximum daily Google API requests, exit the loop
                    }

                    try {
                        $googleClient = $this->setupGoogle();
                        $googleIndexingService = new Google_Service_Indexing($googleClient);

                        $urlNotification = new Google_Service_Indexing_UrlNotification([
                            'url' => $url,
                            'type' => 'URL_UPDATED'
                        ]);

                        $result = $googleIndexingService->urlNotifications->publish($urlNotification);

                        if (isset($result->urlNotificationMetadata)) {
                            // update google status
                            DB::table($value->getTable())->where('id', $value->id)->update(['google_status' => true]);

                            // update indexing history
                            $indexing_history = IndexingHistory::where('url', $url)->where('type', $value->getTable())->first();

                            if ($indexing_history) {
                                $indexing_history->indexing_count++;
                                $indexing_history->save();
                            } else {
                                IndexingHistory::create([
                                    'url' => $url,
                                    'reference_id' => $value->id,
                                    'type' => $value->getTable(),
                                    'indexing_count' => 1
                                ]);
                            }
                        }

                        $googleRequests++; // Increment the Google API requests counter
                    } catch (\Exception $e) {
                        echo 'Caught exception: ', $e->getMessage(), "\n";
                    }
                }

                if (
                    $value->bing_status == false ||
                    ($value->bing_status == true && $indexing_type == 'updated_post')
                ) {
                    if ($bingRequests >= 190) {
                        break; // Reached maximum Bing API requests per day, exit the loop
                    }

                    // Additional logic to handle Bing API quota per day (190 requests)
                    if ($bingRequests >= 10) {
                        sleep(60); // Wait for a minute after reaching 10 requests to stay within the limit of 190 requests per day.
                    }

                    $apiKey = config('bing-indexing.api_credentials');

                    $http = Http::post(
                        'https://www.bing.com/webmaster/api.svc/json/SubmitUrl?apikey=' . $apiKey,
                        [
                            'siteUrl' => url('/'),
                            'url' => $url,
                        ]
                    );

                    if ($http->getStatusCode() == 200) {
                        // update bing status
                        DB::table($value->getTable())->where('id', $value->id)->update(['bing_status' => true]);

                        // update indexing history
                        $indexing_history = IndexingHistory::where('url', $url)->where('type', $value->getTable())->first();

                        if ($indexing_history) {
                            $indexing_history->indexing_count++;
                            $indexing_history->save();
                        } else {
                            IndexingHistory::create([
                                'url' => $url,
                                'reference_id' => $value->id,
                                'type' => $value->getTable(),
                                'indexing_count' => 1
                            ]);
                        }

                        $bingRequests++; // Increment the Bing API requests counter
                        // logic of this indexing
                        //The script prioritizes the order in which posts are submitted for indexing:

                        //If there are new posts ($newPosts), they are indexed first.
                        //If there are no new posts, but there are old posts ($oldPosts), they are indexed.
                        //If there are updated posts ($updatedPosts), they are indexed as well.
                        
                        //The script then fetches categories that need to be indexed on Google or Bing (categories with google_status or bing_status set to 0).
                        //The indexedGoogleBing method is responsible for submitting URLs for indexing to Google and Bing. It loops through the provided collection of posts or categories and performs the following steps for each URL:

                        //   a. It determines whether the URL is a new post, updated post, or old post based on its creation and modification dates.
                        //   b. If the URL's Google indexing status is false, or it's a recently updated post, it submits the URL to Google indexing using the Google API. It also updates the Google indexing status of the post in the database.
                        //   c. If the URL's Bing indexing status is false, or it's a recently updated post, it submits the URL to Bing indexing using the Bing API. It also updates the Bing indexing status of the post in the database.
                        //   The script tracks the number of Google and Bing API requests made and handles API request limits (190 requests per day for Google and 190 requests per day for Bing). If the limit is reached, the script stops submitting URLs and exits the loop
                        //   By repeating this process every 15 days (i.e., old posts between 10 and 20 days old), the script ensures that older content is regularly re-indexed by Google and Bing, enhancing its visibility and searchability in search engine results.
                    }
                }
            }
        }
    }
}
