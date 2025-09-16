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

        // Retrieve new posts created within 10 days and not yet indexed
        $newPosts = Post::where('verified_email', 1)
            ->where('created_at', '>', $tenDaysAgo)
            ->where('google_status', 0)
            ->orWhere(function ($query) use ($tenDaysAgo) {
                $query->where('verified_email', 1)
                    ->where('updated_at', '>', $tenDaysAgo)
                    ->where('google_status', 0);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Retrieve old posts created and updated before 10 days and not yet indexed
        $oldPosts = Post::where('verified_email', 1)
            ->where('created_at', '<=', $tenDaysAgo)
            ->where('updated_at', '<=', $tenDaysAgo)
            ->where('google_status', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        // Retrieve updated posts within 10 days and already indexed
        $updatedPosts = Post::where('verified_email', 1)
            ->where('updated_at', '>', $tenDaysAgo)
            ->where('google_status', 1)
            ->get();

        $posts = collect([]);

        // Prioritize new posts
        if ($newPosts->count() > 0) {
            $posts = $newPosts;
        } elseif ($oldPosts->count() > 0) {
            $posts = $oldPosts;
        }

        if ($posts->count() > 0) {
            $this->indexedGoogleBing($posts);
        }

        if ($updatedPosts->count() > 0) {
            $this->indexedGoogleBing($updatedPosts);
        }

        $categories = Category::where('active', 1)
            ->where(function ($q) {
                $q->where('google_status', 0)
                    ->orWhere('bing_status', 0);
            })
            ->get();

        $this->indexedGoogleBing($categories);

        // Resubmit old posts if no new posts after 10 days
        if ($newPosts->count() == 0 && $oldPosts->count() > 0) {
            $this->resubmitOldPosts($oldPosts);
        }
    }

    private function indexedGoogleBing($values)
    {
        if ($values->count() > 0) {
            foreach ($values as $value) {
                $url = $value->getTable() == 'categories'
                    ? url(UrlGen::category($value, null, null, $value->parent))
                    : url(UrlGen::postUri($value));
                $now = Carbon::now();

                // Check if post is new or updated within 10 days
                $recentlyUpdated = $value->updated_at->diffInDays($now) <= 10;
                $isNewPost = $value->created_at->diffInDays($now) <= 10;

                // Prioritize submitting new posts first
                if ($isNewPost) {
                    $indexingType = 'new_post';
                } elseif ($recentlyUpdated) {
                    $indexingType = 'updated_post';
                } else {
                    $indexingType = 'old_post';
                }

                // Submit to Google indexing
                if ($value->google_status == false || ($value->google_status == true && $indexingType == 'updated_post')) {
                    try {
                        $googleClient = $this->setupGoogle();
                        $googleIndexingService = new Google_Service_Indexing($googleClient);

                        $urlNotification = new Google_Service_Indexing_UrlNotification([
                            'url' => $url,
                            'type' => 'URL_UPDATED',
                        ]);

                        $result = $googleIndexingService->urlNotifications->publish($urlNotification);

                        if (isset($result->urlNotificationMetadata)) {
                            // Update Google status
                            DB::table($value->getTable())
                                ->where('id', $value->id)
                                ->update(['google_status' => true]);

                            // Update indexing history
                            $indexingHistory = IndexingHistory::where('url', $url)
                                ->where('type', $value->getTable())
                                ->first();

                            if ($indexingHistory) {
                                $indexingHistory->indexing_count++;
                                $indexingHistory->save();
                            } else {
                                IndexingHistory::create([
                                    'url' => $url,
                                    'reference_id' => $value->id,
                                    'type' => $value->getTable(),
                                    'indexing_count' => 1,
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        echo 'Caught exception: ' . $e->getMessage() . "\n";
                    }
                }

                // Submit to Bing indexing
                if ($value->bing_status == false || ($value->bing_status == true && $indexingType == 'updated_post')) {
                    $apiKey = config('bing-indexing.api_credentials');

                    $http = Http::post(
                        'https://www.bing.com/webmaster/api.svc/json/SubmitUrl?apikey=' . $apiKey,
                        [
                            'siteUrl' => url('/'),
                            'url' => $url,
                        ]
                    );

                    if ($http->getStatusCode() == 200) {
                        // Update Bing status
                        DB::table($value->getTable())
                            ->where('id', $value->id)
                            ->update(['bing_status' => true]);

                        // Update indexing history
                        $indexingHistory = IndexingHistory::where('url', $url)
                            ->where('type', $value->getTable())
                            ->first();

                        if ($indexingHistory) {
                            $indexingHistory->indexing_count++;
                            $indexingHistory->save();
                        } else {
                            IndexingHistory::create([
                                'url' => $url,
                                'reference_id' => $value->id,
                                'type' => $value->getTable(),
                                'indexing_count' => 1,
                            ]);
                        }
                    }
                }
            }
        }
    }

    private function resubmitOldPosts($oldPosts)
    {
        foreach ($oldPosts as $oldPost) {
            $url = url(UrlGen::postUri($oldPost));

            // Check if the post has not been resubmitted in the last 10 days
            $lastSubmissionDate = IndexingHistory::where('url', $url)
                ->where('type', 'posts')
                ->orderBy('created_at', 'desc')
                ->value('created_at');

            $resubmitPeriod = Carbon::parse($lastSubmissionDate)->addDays(10);

            if (!$lastSubmissionDate || Carbon::now()->greaterThan($resubmitPeriod)) {
                try {
                    $googleClient = $this->setupGoogle();
                    $googleIndexingService = new Google_Service_Indexing($googleClient);

                    $urlNotification = new Google_Service_Indexing_UrlNotification([
                        'url' => $url,
                        'type' => 'URL_UPDATED',
                    ]);

                    $result = $googleIndexingService->urlNotifications->publish($urlNotification);

                    if (isset($result->urlNotificationMetadata)) {
                        // Update Google status
                        DB::table('posts')
                            ->where('id', $oldPost->id)
                            ->update(['google_status' => true]);

                        // Update indexing history
                        IndexingHistory::create([
                            'url' => $url,
                            'reference_id' => $oldPost->id,
                            'type' => 'posts',
                            'indexing_count' => 1,
                        ]);
                    }
                } catch (\Exception $e) {
                    echo 'Caught exception: ' . $e->getMessage() . "\n";
                }
            }
        }
    }
}
