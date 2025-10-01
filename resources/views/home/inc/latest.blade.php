<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.optimization.cache_expiration');
}
$hideOnMobile = '';
if (isset($latestOptions, $latestOptions['hide_on_mobile']) and $latestOptions['hide_on_mobile'] == '1') {
	$hideOnMobile = ' hidden-sm';
}
?>
@if (isset($latest) && !empty($latest) && $latest->posts->count() > 0)
	@includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'], ['hideOnMobile' => $hideOnMobile])
	<div class="container{{ $hideOnMobile }}" style="overflow-x: hidden;">
		<div class="col-xl-12 content-box layout-section">
			<div class="row row-featured row-featured-category">
				
				<div class="col-xl-12 box-title no-border">
					<div class="inner">
						<h2>
							<span class="title-3">{!! $latest->title !!}</span>
							<a href="{{ $latest->link }}" class="sell-your-item" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
								{{ t('View more') }} <i class="icon-th-list"></i>
							</a>
						</h2>
					</div>
				</div>
				
				<div id="postsList" class="adds-wrapper noSideBar" style="overflow-x: hidden;">
					<div class="row" style="display: flex; flex-wrap: wrap;">
						@foreach($latest->posts as $key => $post)
							@continue(empty($post->city))
							<?php
								// Main Picture
								if ($post->pictures->count() > 0) {
									$postImg = imgUrl($post->pictures->get(0)->filename, 'medium');
								} else {
									$postImg = imgUrl(config('larapen.core.picture.default'), 'medium');
								}
							?>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
								<div class="card h-100 position-relative border-0 shadow-sm" style="overflow: hidden; transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='scale(1.02)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='scale(1.0)'; this.style.boxShadow='';">
									@if ($post->featured == 1)
										@if (isset($post->latestPayment, $post->latestPayment->package) && !empty($post->latestPayment->package))
											@if ($post->latestPayment->package->ribbon != '')
												<div class="position-absolute top-0 start-0 m-2 {{ $post->latestPayment->package->ribbon }}" style="z-index: 5; background-color: black; transition: background-color 0.3s;" onmouseover="this.style.backgroundColor='#006400';" onmouseout="this.style.backgroundColor='black';">
													<span class="badge" style="color: white;">{{ $post->latestPayment->package->short_name }}</span>
												</div>
											@endif
										@endif
									@endif
									
									<div class="position-relative">
										<div class="position-absolute top-0 end-0 m-2" style="z-index: 10; display: flex; flex-direction: row; gap: 5px; align-items: center;">
											@if (config('settings.single.show_post_types'))
												@if (isset($post->postType) && !empty($post->postType))
													<span class="badge bg-primary" style="font-size: 0.75rem; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;" data-toggle="tooltip" data-placement="bottom" title="{{ $post->postType->name }}">
														{{ strtoupper(mb_substr($post->postType->name, 0, 1)) }}
													</span>
												@endif
											@endif
											@if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
												<a class="btn btn-success btn-sm make-favorite p-1" id="{{ $post->id }}" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
													<i class="fa fa-heart fa-xs"></i>
												</a>
											@else
												<a class="btn btn-light btn-sm make-favorite p-1" id="{{ $post->id }}" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
													<i class="fa fa-heart fa-xs"></i>
												</a>
											@endif
										</div>
										<a href="{{ \App\Helpers\UrlGen::post($post) }}">
											<img class="card-img-top w-100" src="{{ $postImg }}" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
										</a>
									</div>
									
									<div class="card-body d-flex flex-column p-3">
										<div class="d-flex justify-content-between align-items-start mb-2">
											<h6 class="card-title mb-0 fw-bold" style="font-size: 0.95rem; flex: 1; line-height: 1.3; transition: color 0.3s;">
												<a href="{{ \App\Helpers\UrlGen::post($post) }}" class="text-decoration-none" style="color: black; transition: color 0.3s;" onmouseover="this.style.color='#006400';" onmouseout="this.style.color='black';">
													{{ \Illuminate\Support\Str::limit($post->title, 40) }}
												</a>
											</h6>
											<div class="text-end ms-2">
												<div class="fw-bold" style="font-size: 1rem; color: black; transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='black';">
													@if (isset($post->category, $post->category->type))
														@if (!in_array($post->category->type, ['not-salable']))
															@if (is_numeric($post->price) && $post->price > 0)
																{!! \App\Helpers\Number::money($post->price) !!}
															@elseif(is_numeric($post->price) && $post->price == 0)
																{!! t('free_as_price') !!}
															@else
																{!! \App\Helpers\Number::money(' --') !!}
															@endif
														@endif
													@else
														{{ '--' }}
													@endif
												</div>
											</div>
										</div>
										
										<div class="mb-1 small text-muted" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
											@if (isset($post->category->parent) && !empty($post->category->parent))
												<a href="{!! \App\Helpers\UrlGen::category($post->category->parent) !!}" class="text-decoration-none" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
													{{ $post->category->parent->name }}
												</a> &raquo;
											@endif
											<a href="{!! \App\Helpers\UrlGen::category($post->category) !!}" class="text-decoration-none" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
												{{ $post->category->name }}
											</a>
										</div>
										
										<div class="mb-1 small text-muted" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
											<i class="far fa-clock me-1"></i>
											@if (!config('settings.listing.hide_dates'))
												{!! $post->created_at_formatted !!}
											@endif
										</div>
										
										<div class="small text-muted" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
											<i class="fas fa-map-marker-alt me-1"></i>
											<a href="{!! \App\Helpers\UrlGen::city($post->city) !!}" class="text-decoration-none" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
												{{ $post->city->name }}
											</a>
											{{ (isset($post->distance)) ? '- ' . round($post->distance, 2) . getDistanceUnit() : '' }}
										</div>
										
										@if (config('plugins.reviews.installed'))
											@if (view()->exists('reviews::ratings-list'))
												<div class="mt-2">
													@include('reviews::ratings-list')
												</div>
											@endif
										@endif
									</div>
								</div>
							</div>
						@endforeach
					</div>
			
					<div class="clearfix"></div>
					
					@if (isset($latestOptions) && isset($latestOptions['show_view_more_btn']) && $latestOptions['show_view_more_btn'] == '1')
						<div class="mb-4 text-center mt-4">
							<a href="{{ \App\Helpers\UrlGen::search() }}" class="btn btn-primary px-4" style="transition: color 0.3s;" onmouseover="this.style.color='green';" onmouseout="this.style.color='';">
								<i class="fa fa-arrow-circle-right me-2"></i> {{ t('View more') }}
							</a>
						</div>
					@endif
				</div>
				
			</div>
		</div>
	</div>
@endif

@section('after_scripts')
    @parent
    <script>
		/* Default view (See in /js/script.js) */
		@if (isset($posts) && count($posts) > 0)
			@if (config('settings.listing.display_mode') == '.grid-view')
				gridView('.grid-view');
			@elseif (config('settings.listing.display_mode') == '.list-view')
				listView('.list-view');
			@elseif (config('settings.listing.display_mode') == '.compact-view')
				compactView('.compact-view');
			@else
				gridView('.grid-view');
			@endif
		@else
			listView('.list-view');
		@endif
		/* Save the Search page display mode */
		var listingDisplayMode = readCookie('listing_display_mode');
		if (!listingDisplayMode) {
			createCookie('listing_display_mode', '{{ config('settings.listing.display_mode', '.grid-view') }}', 7);
		}
		
		/* Favorites Translation */
		var lang = {
			labelSavePostSave: "{!! t('Save ad') !!}",
			labelSavePostRemove: "{!! t('Remove favorite') !!}",
			loginToSavePost: "{!! t('Please log in to save the Ads') !!}",
			loginToSaveSearch: "{!! t('Please log in to save your search') !!}",
			confirmationSavePost: "{!! t('Post saved in favorites successfully') !!}",
			confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully') !!}",
			confirmationSaveSearch: "{!! t('Search saved successfully') !!}",
			confirmationRemoveSaveSearch: "{!! t('Search deleted successfully') !!}"
		};
    </script>
@endsection