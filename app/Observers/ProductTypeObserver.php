<?php

namespace App\Observers;

use App\Models\Post;
use App\Models\ProductType;
use Illuminate\Support\Facades\Cache;

class ProductTypeObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param ProductType $productType
	 * @return void
	 */
	public function deleting($productType)
	{
		// Delete all the productType's posts
		$posts = Post::where('post_type_id', $productType->id);
		if ($posts->count() > 0) {
			foreach ($posts->cursor() as $post) {
				$post->delete();
			}
		}
	}
	
	/**
	 * Listen to the Entry saved event.
	 *
	 * @param ProductType $productType
	 * @return void
	 */
	public function saved(ProductType $productType)
	{
		// Removing Entries from the Cache
		$this->clearCache($productType);
	}
	
	/**
	 * Listen to the Entry deleted event.
	 *
	 * @param ProductType $productType
	 * @return void
	 */
	public function deleted(ProductType $productType)
	{
		// Removing Entries from the Cache
		$this->clearCache($productType);
	}
	
	/**
	 * Removing the Entity's Entries from the Cache
	 *
	 * @param $productType
	 */
	private function clearCache($productType)
	{
		Cache::flush();
	}
}
