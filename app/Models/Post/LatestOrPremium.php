<?php
/**

 */

namespace App\Models\Post;

use App\Models\Package;
use App\Models\Payment;
use App\Models\Post;
use App\Models\ProductType;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Collection; // Added: For handling collections

trait LatestOrPremium
{
    /**
     * Get Latest or Sponsored Posts
     *
     * @param int $limit
     * @param string $type
     * @param null $defaultOrder
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getLatestOrSponsored($limit = 20, $type = 'latest', $defaultOrder = null, $serviceProductItems = null, $physicalProductItems = null, $digitalProductItems = null)
    {
        $postsTable = (new Post())->getTable();

        $productTypes = [
            1 => $serviceProductItems,
            2 => $physicalProductItems,
            3 => $digitalProductItems
        ];

        // Initialize an empty collection to store posts
        $postsCollection = new Collection(); // Updated: Initialize collection

        foreach ($productTypes as $productTypeId => $typeLimit) {
            if (is_null($typeLimit) || $typeLimit == 0) {
                continue; // Skip if no items are requested for this product type
            }

            $query = Post::query();

            // Default Filters
            $query->currentCountry()->verified()->unarchived();
            if (config('settings.single.posts_review_activation')) {
                $query->reviewed();
            }

            // Relations
            $query->with(['category', 'postType', 'productType', 'city', 'pictures', 'latestPayment.package', 'savedByLoggedUser']);

            if ($type == 'sponsored') {
                $query->where('featured', 1);
            }

            // Filter by product type
            $query->where('product_type_id', $productTypeId)
                ->where($postsTable . '.created_at', '>=', Carbon::now()->subDays(365))
                ->inRandomOrder()
                ->limit($typeLimit);

            $posts = $query->get();

            // Merge fetched posts into the main collection
            $postsCollection = $postsCollection->merge($posts); // Updated: Merge posts
        }

        // Shuffle the merged collection to ensure randomness
        $shuffledPosts = $postsCollection->shuffle(); // Updated: Shuffle merged collection

        // Limit the total number of posts to the specified limit
        $finalPosts = $shuffledPosts->take($limit); // Updated: Apply final limit

        return $finalPosts->values(); // Ensure clean indexing
    }
}
