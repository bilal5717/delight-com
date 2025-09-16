<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Helpers\UrlGen;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;

class CacheWarmUp extends Command
{
    protected $signature = 'cache:warm-up';
    protected $description = 'Warm up the cache by making requests to critical pages';

    public function handle()
    {
       \Log::info('Cache warm-up command started.');
        $tenDaysAgo = Carbon::now()->subDays(10);

        // Retrieve new, old, and updated posts
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
    
        // Cache warm-up for new, old, and updated posts
        $this->cacheWarmUp($newPosts);
        $this->cacheWarmUp($oldPosts);
        $this->cacheWarmUp($updatedPosts);
    
        // Retrieve categories
        $categories = Category::where("active", 1)
            ->where(function ($q) {
                $q->where("google_status", 0)->orWhere("bing_status", 0);
            })
            ->get();
    
        // Cache warm-up for categories
        $this->cacheWarmUp($categories);
        \Log::info('Cache warm-up command completed.');
    }
    
    private function cacheWarmUp($items)
    {
        foreach ($items as $item) {
            $url = $this->generateUrl($item);
            $response = Http::get($url);
    
            $now = Carbon::now();
    
            // Check if item is new or updated within 10 days
            $recentlyUpdated = $item->updated_at->diffInDays($now) <= 10;
            $isNewItem = $item->created_at->diffInDays($now) <= 10;
    
            // Prioritize submitting new items first
            $indexingType = $isNewItem ? 'new_post' : ($recentlyUpdated ? 'updated_post' : 'old_post');
    
            // Log or process $url and $indexingType as needed
            $this->info("Cache warm-up complete for: $url. Indexing Type: $indexingType");
            \Log::info("Cache warm-up complete for: $url. HTTP Status: {$response->status()}");
        }
    }

    
    
    private function generateUrl($item)
    {
        \Log::info(get_class($item)); // Log the class name for debugging
    
        // Assuming that $item can be either a Post or a Category
        if ($item instanceof Post) {
            return UrlGen::generatePostUrl($item); // Adjust method name as needed
        } elseif ($item instanceof Category) {
            return UrlGen::generateCategoryUrl($item); // Adjust method name as needed
        }
    
        // If $item is neither a Post nor a Category, provide a default behavior
        \Log::warning("Unsupported item type: " . get_class($item));
        return ''; // You can return an empty string or another default URL
    }
    
    private function indexGoogleBing($urls)
    {
        // Implement your logic for indexing Google and Bing based on the provided URLs
        // Use the $urls array to pass the URLs to the indexing process.
        // For example, you might pass them as command arguments or load them from a file/database.
        // Run your indexing process here.
    }
}
