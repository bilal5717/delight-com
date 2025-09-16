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

namespace App\Observers;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\Category;
use App\Models\CategoryField;
use App\Models\Post;
use App\Observers\Traits\CategoryTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use App\Helpers\UrlGen;
use App\Http\Controllers\Traits\GoogleIndexingTrait;
use Google;
use Google_Service_Indexing;
use Google_Service_Indexing_UrlNotification;
use Log;


class CategoryObserver
{
	use CategoryTrait,GoogleIndexingTrait;
	
	/**
	 * Listen to the Entry creating event.
	 *
	 * @param Category $category
	 * @return void
	 */
	public function creating(Category $category)
	{
		// Fix required columns
		$category = $this->fixRequiredColumns($category);
		
		// Apply the nested created actions
		$category = $this->creatingNestedItem($category);
		
		// Set default value as false for google and bing indexing when creating
		$category->google_status = false;
		$category->bing_status = false;
		
		return $category;
	}
	
	/**
	 * Listen to the Entry updating event.
	 *
	 * @param Category $category
	 * @return void
	 */
	public function updating(Category $category)
	{
		// Fix required columns
		$category = $this->fixRequiredColumns($category);
		
		// Apply the nested updating actions
		$category = $this->updatingNestedItem($category);
		
		// Set default value as false for google and bing indexing when updating
		$category->google_status = false;
		$category->bing_status = false;
		
		return $category;
	}
	
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param Category $category
	 * @return void
	 */
	public function deleting($category)
	{
		// Apply the nested deleting actions
		$this->deletingNestedItem($category);
		
		// Storage Disk Init.
		$disk = StorageDisk::getDisk();
		
		// Delete all the Category's Custom Fields
		$catFields = CategoryField::where('category_id', $category->id)->get();
		if ($catFields->count() > 0) {
			foreach ($catFields as $catField) {
				$catField->delete();
			}
		}
		
		// Delete all the Category's Posts
		$posts = Post::where('category_id', $category->id);
		if ($posts->count() > 0) {
			foreach ($posts->cursor() as $post) {
				$post->delete();
			}
		}
		
		// Don't delete the default pictures
		$defaultPicture     = 'app/default/categories/fa-folder-' . config('settings.style.app_skin', 'skin-default') . '.png';
		$defaultSkinPicture = 'app/categories/' . config('settings.style.app_skin', 'skin-default') . '/';
		if (
			!Str::contains($category->picture, $defaultPicture)
			&& !Str::contains($category->picture, $defaultSkinPicture)
			&& $disk->exists($category->picture)
		) {
			$disk->delete($category->picture);
		}
		
		// Delete the category's children recursively
		$this->deleteChildrenRecursively($category);
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param Category $category
	 * @return void
	 */
	public function saved(Category $category)
	{
		// Convert Adjacent List to Nested Set
		// $this->adjacentToNestedByItem($category);
		
		// Removing Entries from the Cache
		$this->clearCache($category);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param Category $category
	 * @return void
	 */
	public function deleted(Category $category)
	{
		// Convert Adjacent List to Nested Set
		// $this->adjacentToNestedByItem($category);

		// Deleting url from google indexing
		try {
			$googleClient = $this->setupGoogle();
			$googleIndexingService = new Google_Service_Indexing( $googleClient );
			$url = url(UrlGen::category($category, null, null,$category->parent));
			
			$urlNotification = new Google_Service_Indexing_UrlNotification([
			  'url' => $url,
			  'type' => 'URL_DELETED'
			]);
		  
			$result = $googleIndexingService->urlNotifications->publish( $urlNotification );
			
		  } 
		  catch (\Exception $e) {
			Log::error($e->getMessage);
		  }
		
		// Removing Entries from the Cache
		$this->clearCache($category);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $category
	 */
	private function clearCache($category)
	{
		Cache::flush();
	}
}
