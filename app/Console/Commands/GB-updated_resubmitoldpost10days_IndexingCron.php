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
  protected $description = 'Add newly created or updated urls to Google and Bing indexing.';

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
    $newPosts = Post::where("verified_email", 1)->where("created_at", ">", $tenDaysAgo)->where("google_status", 0)->orWhere(function ($query) use ($tenDaysAgo) {
      $query->where("verified_email", 1)->where("updated_at", ">", $tenDaysAgo)->where("google_status", 0);
    })->orderBy("created_at", "desc")->get();

    $oldPosts = Post::where("verified_email", 1)->where("created_at", "<=", $tenDaysAgo)->where("updated_at", "<=", $tenDaysAgo)->where("google_status", 0)->orderBy("created_at", "asc")->get();

    $updatedPosts = Post::where("verified_email", 1)->where("updated_at", ">", $tenDaysAgo)->where("google_status", 1)->get();

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

    $categories = Category::where("active", 1)->where(function ($q) {
      $q->where("google_status", 0)->orWhere("bing_status", 0);
    })->get();

    $this->indexedGoogleBing($categories);
  }

  private function indexedGoogleBing($values)
  {
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
        if ($value->google_status == false || ($value->google_status == true && $indexing_type == 'updated_post')) {
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
          } catch (\Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
          }
        }

        if ($value->bing_status == false || ($value->bing_status == true && $indexing_type == 'updated_post')) {

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
          }
        }
      }
    }

    // Submit old posts
    $tenDaysAgo = Carbon::now()->subDays(10);
    $twentyDaysAgo = Carbon::now()->subDays(20);
    $old_posts = Post::where('verified_email', 1)
      ->where(function ($query) use ($tenDaysAgo, $twentyDaysAgo) {
        $query->where(function ($subQuery) use ($tenDaysAgo) {
          $subQuery->where('created_at', '<=', $tenDaysAgo)
            ->where('updated_at', '<=', $tenDaysAgo)
            ->where('google_status', 0);
        })
          ->orWhere(function ($subQuery) use ($twentyDaysAgo) {
            $subQuery->where('created_at', '<=', $twentyDaysAgo)
              ->where('updated_at', '<=', $twentyDaysAgo)
              ->where('google_status', 1);
          });
      })
      ->orderBy('created_at', 'asc')
      ->get();

    foreach ($old_posts as $post) {
      $url = url(UrlGen::postUri($post));

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
          DB::table($post->getTable())->where('id', $post->id)->update(['google_status' => true]);

          // update indexing history
          $indexing_history = IndexingHistory::where('url', $url)->where('type', $post->getTable())->first();

          if ($indexing_history) {
            $indexing_history->indexing_count++;
            $indexing_history->save();
          } else {
            IndexingHistory::create([
              'url' => $url,
              'reference_id' => $post->id,
              'type' => $post->getTable(),
              'indexing_count' => 1
            ]);
          }
        }
      } catch (\Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
      }
    }
  }
}
