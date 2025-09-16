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

namespace App\Http\Controllers\Ajax;

use App\Helpers\UrlGen;
use App\Models\Picture;
use App\Models\Post;
use App\Http\Controllers\FrontController;
use App\Models\SavedPost;
use App\Models\SavedSearch;
use App\Models\PostAddon;
use App\Models\ShippingAddress;
use App\Models\Cart;
use App\Models\PostDuration;
use App\Models\Scopes\VerifiedScope;
use App\Models\Scopes\ReviewedScope;
use Illuminate\Http\Request;
use Larapen\TextToImage\Facades\TextToImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends FrontController
{
    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePost(Request $request)
    {
        $postId = $request->input('postId');

        $status = 0;
        if (auth()->check()) {
            $savedPost = SavedPost::where('user_id', auth()->user()->id)->where('post_id', $postId);
            if ($savedPost->count() > 0) {
                // Delete SavedPost
                $savedPost->delete();
            } else {
                // Store SavedPost
                $savedPostInfo = [
                    'user_id' => auth()->user()->id,
                    'post_id' => $postId,
                ];
                $savedPost = new SavedPost($savedPostInfo);
                $savedPost->save();
                $status = 1;
            }
        }

        $result = [
            'logged' => (auth()->check()) ? auth()->user()->id : 0,
            'postId' => $postId,
            'status' => $status,
            'loginUrl' => UrlGen::login(),
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveSearch(Request $request)
    {
        $queryUrl = $request->input('url');
        $tmp = parse_url($queryUrl);
        $query = $tmp['query'];
        parse_str($query, $tab);
        $keyword = $tab['q'];
        $countPosts = $request->input('countPosts');
        if ($keyword == '') {
            return response()->json([], 200, [], JSON_UNESCAPED_UNICODE);
        }

        $status = 0;
        if (auth()->check()) {
            $savedSearch = SavedSearch::where('user_id', auth()->user()->id)->where('keyword', $keyword)->where('query', $query);
            if ($savedSearch->count() > 0) {
                // Delete SavedSearch
                $savedSearch->delete();
            } else {
                // Store SavedSearch
                $savedSearchInfo = [
                    'country_code' => config('country.code'),
                    'user_id' => auth()->user()->id,
                    'keyword' => $keyword,
                    'query' => $query,
                    'count' => $countPosts,
                ];
                $savedSearch = new SavedSearch($savedSearchInfo);
                $savedSearch->save();
                $status = 1;
            }
        }

        $result = [
            'logged' => (auth()->check()) ? auth()->user()->id : 0,
            'query' => $query,
            'status' => $status,
            'loginUrl' => UrlGen::login(),
        ];

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPhone(Request $request)
    {
        $postId = $request->input('postId', 0);

        $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', $postId)->first(['phone']);

        if (empty($post)) {
            return response()->json(['error' => ['message' => t("Error. Post doesn't exist.")], 404]);
        }

        $phone = $post->phone;
        $phoneModal = $post->phone;
        $phoneLink = 'tel:' . $post->phone;

        if (config('settings.single.convert_phone_number_to_img')) {
            try {
                $phone = TextToImage::make($post->phone, config('larapen.core.textToImage'));
            } catch (\Exception $e) {
                $phone = $post->phone;
            }
        }

        if (config('settings.single.show_security_tips') == '1') {
            $phone = t('phone_number');
        }

        $data = [
            'phone' => $phone,
            'phoneModal' => $phoneModal,
            'link' => $phoneLink,
        ];

        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function picturesReorder(Request $request)
    {
        $params = $request->input('params');

        $result = ['status' => 0];

        if (auth()->check()) {
            if (isset($params['stack']) && count($params['stack']) > 0) {
                $statusOk = false;
                foreach ($params['stack'] as $position => $item) {
                    if (isset($item['key']) && !empty($item['key'])) {
                        $picture = Picture::find($item['key']);
                        if (!empty($picture)) {
                            $picture->position = $position;
                            $picture->save();

                            $statusOk = true;
                        }
                    }
                }
                if ($statusOk) {
                    $result = ['status' => 1];
                }
            }
        }

        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }

    public function updatePost(Request $request)
    {
        $updateParamName = $request->input('updateParam');
        $updateParamValue = $request->input('paramValue');
        $postId = $request->input('postId');
        $updatedPost = Post::where('id', $postId)
            ->update([
                $updateParamName => $updateParamValue
            ]);

        return response()->json($updatedPost, 200, [
            // Standard cache control headers
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, private',
            'Pragma' => 'no-cache',
            'Expires' => 'Sun, 02 Jan 1990 00:00:00 GMT',

            // LiteSpeed specific headers
            'X-LiteSpeed-Cache-Control' => 'no-cache, private',
        ], JSON_UNESCAPED_UNICODE);
    }

    public function retrieveData(Request $request)
    {
        $postId = $request->input('postId');
        $userId = auth()->id();
        $bookingData = Post::find($postId);
        $timeRange = $bookingData->time_range;
        $dateRange = $bookingData->date_range;
        $durations = PostDuration::where('post_id', $postId)->get();

        $timeRangeData = json_decode($timeRange, true);
        $dateRangeData = json_decode($dateRange, true);
        $company = \App\Models\Company::where('user_id', $userId)->first();
         $addresses = [];
        if ($company) {
            $addresses = \App\Models\ShippingAddress::where('company_id', $company->id)->get();
        }
        $data = [
            "bookingdata" => $bookingData,
            "time_range" => $timeRangeData,
            "dateRangeData" => $dateRangeData,
            "shipping_addresses" => $addresses,
            "postDurations" => $durations
        ];

        return response()->json(['selectedValue' => $data], 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function updateAddon(Request $request)
    {
        // Retrieve the incoming request data
        $data = $request->all();
        $id = $data['id'];
        $post_default_id = $data['default_addon_id'];
        $post_id = $data['post_id'];
        $status = $data['status'];
        $amount = $data['amount'];
        $title = $data['title'];

        // Attempt to find the existing addon based on post_id and default_addon_id
        $addon = PostAddon::where('post_id', $post_id)
            ->where('default_addon_id', $post_default_id)
            ->where('id', $id)
            ->first();

        if ($addon) {
            // Update the addon with the new values using mass assignment
            $updatedPost = PostAddon::where('id', $id)
                ->update([
                    'status' => $status,
                    'title' => $title,
                    'amount' => $amount
                ]);

            // Return the updated addon data as a response
            return response()->json([
                'success' => true,
                'message' => 'Addon updated successfully',
                'data' => $updatedPost,
            ]);
        } else {
            // If no matching addon was found, generate a new unique id for the addon
            $maxId = PostAddon::max('id'); // Get the maximum existing id
            $newId = $maxId + 1; // Generate the new unique id

            // Create a new addon with the generated unique id
            $newAddon = PostAddon::create([
                'id' => $newId,
                'post_id' => $post_id,
                'default_addon_id' => $post_default_id,
                'status' => $status,
                'title' => $title,
                'amount' => $amount,
            ]);

            // Return the newly created addon data as a response
            return response()->json([
                'success' => true,
                'message' => 'Addon created successfully',
                'data' => $newAddon,
            ]);
        }
    }

    public function storeMultiAddons(Request $request)
    {
        // Retrieve the incoming request data
        $addonsData = $request->input('addons', []); // Expecting 'addons' to be an array of data

        $responses = []; // To collect responses for each processed addon
        foreach ($addonsData as $data) {
            $id = $data['id'] ?? null;
            $postDefaultId = $data['default_addon_id'] ?? 0;
            $postId = $data['post_id'] ?? null;
            $status = $data['status'] ?? 'inactive';
            $amount = $data['amount'] ?? 0;
            $title = $data['title'] ?? '';

            if (!$postId || !$title) {
                $responses[] = [
                    'success' => false,
                    'message' => 'Post ID and title are required.',
                    'data' => $data,
                ];
                continue;
            }

            // Check if the addon exists
            $addon = PostAddon::where('post_id', $postId)
                ->where('default_addon_id', $postDefaultId)
                ->where('id', $id)
                ->first();

            if ($addon) {
                // Update the existing addon
                $addon->update([
                    'status' => $status,
                    'title' => $title,
                    'amount' => $amount,
                ]);

                $responses[] = [
                    'success' => true,
                    'message' => 'Addon updated successfully.',
                    'data' => $addon,
                ];
            } else {
                // If addon doesn't exist, create a new one
                $newAddon = PostAddon::create([
                    'post_id' => $postId,
                    'default_addon_id' => $postDefaultId,
                    'status' => $status,
                    'title' => $title,
                    'amount' => $amount,
                ]);

                $responses[] = [
                    'success' => true,
                    'message' => 'Addon created successfully.',
                    'data' => $newAddon,
                ];
            }
        }

        // Return a response with all processed results
        return response()->json([
            'success' => true,
            'message' => 'Addons processed successfully.',
            'responses' => $responses,
        ]);
    }

    public function StoreCartsDetails(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id',
            'duration_id' => 'nullable|exists:post_durations,id',
            'time_slots' => 'nullable|array',
            'time_slots.*.day' => 'sometimes|required|string',
            'time_slots.*.open_time' => 'sometimes|required|date_format:H:i',
            'time_slots.*.close_time' => 'sometimes|required|date_format:H:i',
            'base_price' => 'required|numeric',
            'addons' => 'nullable|array',
            'addons_total' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);
    
        // Prepare data for comparison
        $compareData = [
            'user_id' => $validated['user_id'],
            'post_id' => $validated['post_id'],
            'duration_id' => $validated['duration_id'] ?? null,
        ];
    
        // Check if similar cart item exists
        $cartItem = Cart::where($compareData)
            ->when(!empty($validated['time_slots']), function($query) use ($validated) {
                $query->whereJsonContains('time_slots', $validated['time_slots']);
            })
            ->when(!empty($validated['addons']), function($query) use ($validated) {
                $query->whereJsonContains('addons', $validated['addons']);
            })
            ->first();
    
        if ($cartItem) {
            // Update existing item
            $cartItem->update([
                'quantity' => $cartItem->quantity + 1,
                'addons_total' => $validated['addons_total'],
                'total_price' => $validated['total_price'],
            ]);
        } else {
            // Create new item
            $validated['quantity'] = 1;
            $cartItem = Cart::create($validated);
        }
    
        // Get updated cart count for the user
        $cartCount = Cart::where('user_id', $validated['user_id'])->count();
    
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'cart' => $cartItem,
            'cart_count' => $cartCount
        ]);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::find($request->item_id);

        if ($cart) {
            $cart->update(['quantity' => $request->quantity]);

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'updated_quantity' => $cart->quantity
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Cart item not found'], 404);
    }


   public function updateDurations(Request $request)
{
    $validator = Validator::make($request->all(), [
        'postId' => 'required|exists:posts,id',
        'durations' => 'array',
        'durations.*.duration_title' => 'nullable|string|max:255',
        'durations.*.duration_value' => 'nullable|integer|min:0',
        'durations.*.max_capacity' => 'required|integer|min:0',
        'durations.*.duration_unit' => 'nullable|string|in:minutes,hours,days,weeks,months,years',
        'durations.*.open_time' => 'nullable|date_format:H:i', // Add validation for time format
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();
    try {
        $postId = $request->postId;
        $existingIds = PostDuration::where('post_id', $postId)->pluck('id')->toArray();
        $submittedIds = [];

        foreach ($request->durations as $durationData) {
            $durationData = array_merge($durationData, [
                'post_id' => $postId,
                'available_units' => $durationData['max_capacity'], // Set available units same as capacity initially
                'open_time' => $durationData['open_time'] ?? null, // Include open_time in the data
            ]);

            // Create or update using the PostDuration model
            $duration = PostDuration::updateOrCreate(
                ['id' => $durationData['id'] ?? null],
                $durationData
            );

            $submittedIds[] = $duration->id;
        }

        // Delete durations that weren't submitted
        PostDuration::where('post_id', $postId)
            ->whereNotIn('id', $submittedIds)
            ->delete();

        DB::commit();

        // Return the saved data for verification
        $savedDurations = PostDuration::where('post_id', $postId)->get();
        return response()->json([
            'success' => true,
            'data' => $savedDurations
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error saving durations: ' . $e->getMessage()
        ], 500);
    }
}
    private function getDurationUnit($durationData)
    {
        if ($durationData['duration_type'] === 'custom') {
            return $durationData['duration_unit'] ?? 'minutes';
        }

        $minutes = (int)$durationData['duration_value'];
        $units = [
            525600 => 'years',
            43200 => 'months',
            10080 => 'weeks',
            1440 => 'days',
            60 => 'hours',
            1 => 'minutes'
        ];

        foreach ($units as $minutesInUnit => $unit) {
            if ($minutes % $minutesInUnit === 0) {
                return $unit;
            }
        }
        return 'minutes';
    }

    public function deleteDuration(Request $request)
    {
        $request->validate([
            'postId' => 'required|exists:posts,id',
            'durationId' => 'required|exists:post_durations,id'
        ]);

        PostDuration::where('id', $request->durationId)
            ->where('post_id', $request->postId)
            ->delete();

        return response()->json(['success' => true]);
    }
}
