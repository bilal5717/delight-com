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

namespace App\Models\Post;

use App\Models\Category;
use App\Models\Post;

trait SimilarByVendor
{
	/**
	 * Get similar Posts (Posts in the same Category)
	 *
	 * @param int $limit
	 * @return \Illuminate\Support\Collection
	 */
	public function getSimilarByVendor($user, $limit = 20)
	{
		$posts = Post::query();
		
		$postsTable = (new Post())->getTable();
		
		$select = [
			$postsTable . '.id',
			$postsTable . '.country_code',
			'category_id',
			'title',
			$postsTable . '.price',
			'featured',
			'reviewed',
			'verified_email',
			'verified_phone',
			'rating_cache',
			'rating_count',
			$postsTable . '.created_at',
			$postsTable . '.archived_at',
		];
		
		if (is_array($select) && count($select) > 0) {
			foreach ($select as $column) {
				$posts->addSelect($column);
			}
		}
		
		// Default Filters
		$posts->currentCountry()->verified()->unarchived();
		if (config('settings.single.posts_review_activation')) {
			$posts->reviewed();
		}
		$posts->where("user_id", $user->id);
		// Relations
		$posts->with('category')->has('category');
		$posts->with('pictures');
		$posts->with('city')->has('city');
		
		// Set ORDER BY
		$posts->orderBy('created_at', 'DESC');
		
		$posts = $posts->take((int)$limit)->get();
		
		// Randomize the Posts
		$posts = collect($posts)->shuffle();
		
		return $posts;
	}
}
