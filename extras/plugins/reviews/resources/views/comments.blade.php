<div class="tab-pane reviews-widget"
	 id="item-{{ config('plugins.reviews.name') }}"
	 role="tabpanel"
	 aria-labelledby="item-{{ config('plugins.reviews.name') }}-tab"
>
	<div class="row">
		
		@if (isset($post) and !empty($post))
			<?php
			if (!isset($rvPost) || empty($rvPost)) {
				$rvPost = \extras\plugins\reviews\app\Models\Post::withoutGlobalScopes([
					\App\Models\Scopes\ActiveScope::class,
					\App\Models\Scopes\ReviewedScope::class
				])->find($post->id);
			}
			?>
			
			@if (isset($rvPost) and !empty($rvPost))
				<?php
				// Get all reviews that are not spam for the product and paginate them
				$reviews = $rvPost->reviews()->with('user')->approved()->notSpam()->orderBy('created_at', 'desc')->paginate(20);
				?>
				<div class="col-md-12 well" id="reviews-anchor">
					
					@if (Session::get('errors'))
						<div class="row pt-3">
							<div class="col-md-12">
								<div class="alert alert-danger mb-0">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<h5><strong>{{ t('There were errors while submitting this review') }}:</strong></h5>
									<ul class="list list-check">
										@foreach ($errors->all() as $error)
											<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
							</div>
						</div>
					@endif
					@if (Session::has('review_posted'))
						<div class="row pt-3">
							<div class="col-md-12">
								<div class="alert alert-success mb-0">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<p class="mb-0"><strong>{{ t('Your review has been posted!') }}</strong></p>
								</div>
							</div>
						</div>
					@endif
					@if (Session::has('review_removed'))
						<div class="row pt-3">
							<div class="col-md-12">
								<div class="alert alert-success mb-0">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									<p class="mb-0"><strong>{{ t('Your review has been removed!') }}</strong></p>
								</div>
							</div>
						</div>
					@endif
					
					<div class="row pb-3" id="post-review-box">
						@if (!auth()->check() and !config('settings.reviews.guests_comments'))
							<div class="col-md-12 text-center pb-3">
								<div class="row">
									<div class="col-12 mb-3">
										<strong>{{ t('Note') }}:</strong> {{ t('You must be logged in to post a review.') }}
									</div>
									<div class="col-12">
										<form action="{{ \App\Helpers\UrlGen::login() }}" method="post" class="m-0 p-0">
											{!! csrf_field() !!}
											<div class="row d-flex justify-content-center">
												<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12 pl-1 pr-1">
													<div class="form-group">
														<input type="text" class="form-control" name="login" placeholder="{{ getLoginLabel() }}">
													</div>
												</div>
												<div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-12 pl-1 pr-1">
													<div class="form-group">
														<input type="password" class="form-control" name="password" placeholder="{{ t('password') }}">
													</div>
												</div>
												<div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col-xs-12 pl-1 pr-1">
													<input type="hidden" name="{{ md5('noAaboQuery') }}" value="1">
													<button type="submit" class="btn btn-primary btn-block">{{ t('login') }}</button>
												</div>
												@if (
												config('settings.security.recaptcha_activation')
												&& config('recaptcha.site_key')
												&& config('recaptcha.secret_key')
												)
													<div style="clear: both;" class="mb-5"></div>
												@endif
												@include('layouts.inc.tools.recaptcha', ['noLabel' => true])
											</div>
										</form>
									</div>
								</div>
							</div>
						@else
							@if (
								(auth()->check() and isset($rvPost, $rvPost->user) and auth()->user()->id != $rvPost->user->id)
								or (!auth()->check() and config('settings.reviews.guests_comments'))
							)
								<div class="col-md-12">
									<form id="reviewsForm" method="POST" action="{{ url('posts/' . $rvPost->id . '/review/create') }}">
										{!! csrf_field() !!}
										<input type="hidden" name="rating" id="rating">
										
										<div class="form-group row required mb-0 <?php echo (isset($errors) and $errors->has('comment')) ? 'has-error' : ''; ?>">
											<div class="col-md-12 pt-3 pl-3 pr-3 pb-0">
                                        <textarea name="comment"
												  id="comment"
												  rows="5"
												  class="form-control animated"
												  placeholder="{{ t('Enter your review here...') }}"
										>{{ old('comment') }}</textarea>
											</div>
										</div>
										
										<div class="form-group row">
											<div class="col-md-12 text-right">
												<div class="stars starrr" data-rating="{{ old('rating', 0) }}"></div>
												<button class="btn btn-success btn-lg" type="submit">{{ t('Leave a Review') }}</button>
											</div>
										</div>
									</form>
								</div>
							@endif
						
						@endif
					</div>
					
					@if (
							(auth()->check() and isset($rvPost, $rvPost->user) and auth()->user()->id != $rvPost->user->id)
							or (!auth()->check() and config('settings.reviews.guests_comments'))
						)
						<hr>
					@endif
					
					@if ($reviews->count() > 0)
						@foreach($reviews as $review)
							<div class="row comments">
								<div class="col-md-12">
									@for ($i=1; $i <= 5 ; $i++)
										<span class="icon-star{{ ($i <= $review->rating) ? '' : '-empty'}}"></span>
									@endfor
									
									<span class="rating-label">
                                    	{{ $review->user ? $review->user->name : t('Anonymous') }}
										@if (auth()->check() and isset($review->user))
											@if (
												auth()->user()->id == $review->user->id
												or auth()->user()->hasAllPermissions(\App\Models\Permission::getStaffPermissions())
											)
												[<a href="{{ url('review/delete/' . $review->id) }}" class="delete-comment">{{ t('Delete') }}</a>]
											@endif
										@endif
                                	</span>
									<span class="pull-right">{!! $review->created_at_formatted !!}</span>
									
									<p>{!! $review->comment !!}</p>
								
								</div>
							</div>
						@endforeach
						
						<nav class="mb-3">
							{{ $reviews->links() }}
						</nav>
					@else
						@if ((auth()->check() and isset($rvPost, $rvPost->user) and auth()->user()->id == $rvPost->user->id))
							<p>{{ t('Your ad has no reviews yet.') }}</p>
						@else
							@if (auth()->check() or config('settings.reviews.guests_comments'))
								<p>{{ t('This ad has no reviews yet. Be the first to leave a review.') }}</p>
							@endif
						@endif
					@endif
				</div>
			@endif
		
		@endif
	
	</div>
</div>

@section('after_styles')
	@parent
	<link href="{{ url('assets/reviews/js/starrr.css') }}" rel="stylesheet" type="text/css">
	<style type="text/css">
		.items-details .tab-content {
			padding-top: 5px;
			padding-bottom: 5px;
		}
		
		.items-details .well {
			margin-bottom: 0;
			border: 0;
			background-color: #fafafa;
		}
		
		#item-reviews {
			margin-top: 0;
		}
		
		#item-reviews > div {
			padding: 0 10px;
		}
		
		/* Enhance the look of the textarea expanding animation */
		.reviews-widget .animated {
			-webkit-transition: height 0.2s;
			-moz-transition: height 0.2s;
			transition: height 0.2s;
		}
		
		.reviews-widget .stars {
			margin: 20px 0;
			font-size: 24px;
			color: #ffc32b;
		}
		
		.reviews-widget .stars a {
			color: #ffc32b;
		}
		
		.reviews-widget .comments span.icon-star,
		.reviews-widget .comments span.icon-star-empty {
			margin-top: 5px;
			font-size: 16px;
			@if (config('lang.direction') == 'rtl')
 				margin-left: -8px;
			@else
 				margin-right: -8px;
			@endif
		}
		
		.reviews-widget .comments .rating-label {
			margin-top: 5px;
			font-size: 16px;
			@if (config('lang.direction') == 'rtl')
 				margin-right: 4px;
			@else
 				margin-left: 4px;
			@endif
		}
		
		@media (min-width: 576px) {
			#post-review-box .form-group {
				margin-bottom: 0;
			}
			
			#post-review-box .form-control {
				width: 100%;
			}
		}
	</style>
@endsection

@section('after_scripts')
	@parent
	<script src="{{ url('assets/reviews/js/autosize.js') }}"></script>
	<script src="{{ url('assets/reviews/js/starrr.js') }}"></script>
	
	<script>
		$(document).ready(function () {
			/* Initialize the autosize plugin on the review text area */
			autosize($('#comment'));
			
			/* Bind the change event for the star rating - store the rating value in a hidden field */
			$('.starrr').starrr({
				change: function (e, value) {
					$('#rating').val(value);
				}
			});
			
			/* Delete comments confirmation */
			$(document).on('click', '.delete-comment', function (e) {
				e.preventDefault(); /* prevents the submit or reload */
				var confirmation = confirm("<?php echo trans('admin.confirm_this_action'); ?>");
				
				if (confirmation) {
					var deleteCommentUrl = $(this).attr('href');
					redirect(deleteCommentUrl);
				}
				
				return false;
			});
		});
	</script>
@endsection