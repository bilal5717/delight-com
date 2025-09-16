``{{--

--}}
<?php
$plugins = array_keys((array)config('plugins'));
$publicDisk = \Storage::disk(config('filesystems.default'));
?>
		<!DOCTYPE html>
<html lang="{{ ietfLangTag(config('app.locale')) }}"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta http-equiv="x-dns-prefetch-control" content="on">

	<!-- Meta Tags -->
	<title>{!! MetaTag::get('title') !!}</title>
	{!! MetaTag::tag('description') !!}
	{!! MetaTag::tag('keywords') !!}

	@php
		use Illuminate\Support\Str;

        $currentPath = request()->path();
        $supportedLanguages = config('sitemap-languages.supported');
        $defaultUrl = url('/');
        $langUrls = [];

        // Determine route type and slug if necessary
        $routeMap = [
            'page' => 'page/',
            'sitemap' => 'sitemap',
            'contact' => 'contact',
            'pricing' => 'pricing',
            'create' => 'create',
            'register' => 'register',
            'login' => 'login',
            'password/reset' => 'password/reset',
        ];

        $routeKey = null;
        $routeSlug = null;

        foreach ($routeMap as $key => $prefix) {
            if (Str::startsWith($currentPath, $prefix)) {
                $routeKey = $key;

                // For "page" routes, extract slug
                if ($key === 'page') {
                    $routeSlug = preg_replace('#^page/([^/]+).*$#', '$1', $currentPath);
                }

                break;
            }
        }
	// Safety check
		if (!is_array($supportedLanguages) || empty($supportedLanguages)) {
			$supportedLanguages = ['en'];
		}
        foreach ($supportedLanguages as $langCode) {
            switch ($routeKey) {
                case 'page':
                    $langUrls[$langCode] = url("page/{$routeSlug}/lang/{$langCode}");
                    $defaultUrl = url("page/{$routeSlug}");
                    break;
                case 'sitemap':
                case 'contact':
                case 'pricing':
                case 'create':
                case 'register':
                case 'login':
                case 'password/reset':
                    $langUrls[$langCode] = url("{$routeKey}/lang/{$langCode}");
                    $defaultUrl = url($routeKey);
                    break;
                default:
                    $langUrls[$langCode] = url("lang/{$langCode}");
                    $defaultUrl = url('/');
                    break;
            }
        }

	@endphp

			<!-- hreflang Tags for SEO -->
	@foreach ($langUrls as $code => $url)
		<link rel="alternate" hreflang="{{ $code }}" href="{{ $url }}" />
	@endforeach
	<link rel="alternate" hreflang="x-default" href="{{ $langUrls['sk'] ?? $defaultUrl }}" />

	<!-- Canonical tag to point to the correct language version -->
	<link rel="canonical" href="{{ $langUrls[app()->getLocale()] ?? ($langUrls['sk'] ?? $defaultUrl) }}" />

	@includeFirst([config('larapen.core.customizedViewPath') . 'common.meta-robots', 'common.meta-robots'])
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="apple-mobile-web-app-title" content="{{ config('settings.app.app_name') }}">
	@php
		// Get the current route name to determine the context (main page or other pages)
        $currentRouteName = \Route::currentRouteName();

        // Get the current month in lowercase (e.g., 'november')
        $currentMonth = strtolower(now()->format('F'));
        $countryCode = config('country.code', 0); // Assuming country code is stored in config

        // Initialize variables
        $picture = null;
        $bannerExists = false;
        $bannerUrl = null;

        // Fetch only posts where country_code matches the given value
        $posts = App\Models\Post::whereHas('pictures', function ($query) use ($countryCode) {
            $query->where('country_code', $countryCode);
        })->get(); // Retrieve all posts with matching country_code

        // Get the first post's first picture if any posts exist
        if ($posts->isNotEmpty()) {
            $firstPost = $posts->first();
            $picture = $firstPost->pictures()->first(); // Get the first picture for the first post

            if ($picture) {
                // Assuming the picture filename includes the path structure within the storage directory
                $bannerPath = storage_path("app/public/" . $picture->filename);
                $bannerExists = file_exists($bannerPath);
                // If the image exists, generate the URL
                $bannerUrl = $bannerExists ? asset("storage/" . $picture->filename) : null;
            }
        }
	@endphp

	@if($currentRouteName === 'main' && $bannerExists)
		<!-- Preload the dynamically generated banner image path -->
		<link rel="preload" href="{{ $bannerUrl }}" as="image" type="image/webp">
	@elseif(($currentRouteName === 'post' || $currentRouteName === 'contact') && $picture)
		@php
			// Example: Handle product pictures (webp or jpg in case of jpg exists)
            $productImagePath = storage_path("app/public/" . $picture->filename); // Adjust the path accordingly
            $productImageExists = file_exists($productImagePath);
            $productImageUrl = $productImageExists ? asset("storage/" . $picture->filename) : null;
		@endphp

		@if($productImageExists)
			<link rel="preload" href="{{ $productImageUrl }}" as="image" type="image/webp">
		@endif
	@endif
	<!-- Preload critical fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<!-- Preload fonts -->
	<link rel="preload" href="https://fonts.googleapis.com/css?family=Raleway:300,400,600&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link rel="preload" href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
	<link rel="preload" href="{{ url(mix('css/app.css')) }}" as="style">
	<link rel="stylesheet" href="{{ url(mix('css/app.css')) }}" media="screen">
	<!-- icon render -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-144-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-114-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-72-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-57-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="16x16" href="{{ $publicDisk->url('app/default/ico/favicon.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-57-precomposed.png') . getPictureVersion() }}">
	<link rel="shortcut icon" href="{{ imgUrl(config('settings.app.favicon'), 'favicon') }}">
	<!-- Prefetch, DNS Prefetch, and x-DNS Prefetch  -->
	<link rel="preconnect" href="{{ request()->fullUrl() }}">
	<link rel="prefetch" href="{{ request()->fullUrl() }}">
	<link rel="dns-prefetch" href="{{ request()->fullUrl() }}">
	@if (!preg_match('/(admin|account)/i', request()->path()))
		<script defer src="https://cloud.umami.is/script.js" data-website-id="f189efd2-4d0a-4bdf-8e1d-7c77f5da6e70"></script>
		<script defer src="https://cloud.umami.is/script.js" data-website-id="acf0ca7a-c2ab-4f3d-9fe6-4f7414b05701"></script>
	@endif
	@if (isset($post))
		@if (isVerifiedPost($post))
			@if (config('services.facebook.client_id'))
				<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
			@endif
			{{-- {!! $og->renderTags() !!} --}}
			{!! MetaTag::twitterCard() !!}
		@endif
	@else
		@if (config('services.facebook.client_id'))
			<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
		@endif
		{{-- {!! $og->renderTags() !!} --}}
		{!! MetaTag::twitterCard() !!}
	@endif
	@include('feed::links')
	{!! seoSiteVerification() !!}

	@if (file_exists(public_path('manifest.json')))
		<link rel="manifest" href="/manifest.json">
	@endif

	@stack('before_styles_stack')
	@yield('before_styles')

	@if (config('lang.direction') == 'rtl')
		<link rel="prefetch" content="on" href="https://fonts.googleapis.com/css?family=Cairo|Changa" rel="stylesheet">
		<link href="{{ url(mix('css/app.rtl.css')) }}" rel="stylesheet">
	@else
		<link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
		<link rel="stylesheet" href="{{ asset('css/app.css') }}">
	@endif
	@if (config('plugins.detectadsblocker.installed'))
		<link href="{{ url('assets/detectadsblocker/css/style.css') . getPictureVersion() }}" rel="stylesheet">
	@endif

	@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.style', 'layouts.inc.tools.style'])

	<link href="{{ url()->asset('css/custom.css') . getPictureVersion() }}" rel="stylesheet">

	@stack('after_styles_stack')
	@yield('after_styles')

	@if (isset($plugins) and !empty($plugins))
		@foreach($plugins as $plugin)
			@yield($plugin . '_styles')
		@endforeach
	@endif

	@if (config('settings.style.custom_css'))
		{!! printCss(config('settings.style.custom_css')) . "\n" !!}
	@endif
	<style>
		html,body{
			overflow-x: hidden;
		}
	</style>
	@if (config('settings.other.js_code'))
		{!! printJs(config('settings.other.js_code')) . "\n" !!}
	@endif

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script>
		paceOptions = {
			elements: true
		};
	</script>
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/material-icons.css') }}">
	<script src="{{ url()->asset('assets/js/pace.min.js') }}" defer></script>
	<script src="{{ url()->asset('assets/plugins/modernizr/modernizr-custom.js') }}" defer></script>

	@section('recaptcha_scripts')
		@if (
			config('settings.security.recaptcha_activation')
			and config('recaptcha.site_key')
			and config('recaptcha.secret_key')
		)
			<style>
				.is-invalid .g-recaptcha iframe,
				.has-error .g-recaptcha iframe {
					border: 1px solid #f85359;
				}
			</style>
			@if (config('recaptcha.version') == 'v3')
				<script type="text/javascript" defer>
					function myCustomValidation(token) {
						/* read HTTP status */
						/* console.log(token); */

						if ($('#gRecaptchaResponse').length) {
							$('#gRecaptchaResponse').val(token);
						}
					}
				</script>
				{!! recaptchaApiV3JsScriptTag([
                    'action'            => request()->path(),
                    'custom_validation' => 'myCustomValidation',
                    'defer'             => true // Update: Explicitly added 'defer' for consistency.
                ]) !!}
			@else
				<!-- Update: Modified recaptchaApiJsScriptTag to add defer -->
				{!! str_replace('<script ', '<script defer ', recaptchaApiJsScriptTag()) !!}
			@endif
		@endif
	@show
</head>
<body class="{{ config('app.skin') }}">
<div id="wrapper">

	@section('header')
		@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.header', 'layouts.inc.header'])
	@show

	@section('search')
	@show

	@section('wizard')
	@show

	@if (isset($siteCountryInfo))
		<div class="h-spacer"></div>
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						{!! $siteCountryInfo !!}
					</div>
				</div>
			</div>
		</div>
	@endif

	@yield('content')

	@section('info')
	@show

	@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.auto', 'layouts.inc.advertising.auto'])

	@section('footer')
		@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.footer', 'layouts.inc.footer'])
	@show

</div>

@section('modal_location')
@show
@section('modal_abuse')
@show
@section('modal_message')
@show

@includeWhen(!auth()->check(), 'layouts.inc.modal.login')
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.change-country', 'layouts.inc.modal.change-country'])
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.error', 'layouts.inc.modal.error'])
@include('cookieConsent::index')

@if (config('plugins.detectadsblocker.installed'))
	@if (view()->exists('detectadsblocker::modal'))
		@include('detectadsblocker::modal')
	@endif
@endif

<script>
	{{-- Init. Root Vars --}}
	var siteUrl = '{{ url('/') }}';
	var languageCode = '<?php echo config('app.locale'); ?>';
	var countryCode = '<?php echo config('country.code', 0); ?>';
	var timerNewMessagesChecking = <?php echo (int)config('settings.other.timer_new_messages_checking', 0); ?>;
	var isLogged = <?php echo (auth()->check()) ? 'true' : 'false'; ?>;
	var isLoggedAdmin = <?php echo (auth()->check() && auth()->user()->can(\App\Models\Permission::getStaffPermissions())) ? 'true' : 'false'; ?>;

	{{-- Init. Translation Vars --}}
	var langLayout = {
		'hideMaxListItems': {
			'moreText': "{{ t('View More') }}",
			'lessText': "{{ t('View Less') }}"
		},
		'select2': {
			errorLoading: function(){
				return "{!! t('The results could not be loaded') !!}"
			},
			inputTooLong: function(e){
				var t = e.input.length - e.maximum, n = {!! t('Please delete X character') !!};
				return t != 1 && (n += 's'),n
			},
			inputTooShort: function(e){
				var t = e.minimum - e.input.length, n = {!! t('Please enter X or more characters') !!};
				return n
			},
			loadingMore: function(){
				return "{!! t('Loading more results') !!}"
			},
			maximumSelected: function(e){
				var t = {!! t('You can only select N item') !!};
				return e.maximum != 1 && (t += 's'),t
			},
			noResults: function(){
				return "{!! t('No results found') !!}"
			},
			searching: function(){
				return "{!! t('Searching') !!}"
			}
		}
	};
	var fakeLocationsResults = "{{ config('settings.listing.fake_locations_results', 0) }}";
	var stateOrRegionKeyword = "{{ t('area') }}";
	var errorText = {
		errorFound: "{{ t('error_found') }}"
	};
</script>

@stack('before_scripts_stack')
@yield('before_scripts')

<script src="{{ url(mix('js/app.js')) }}"></script>
@if (config('settings.optimization.lazy_loading_activation') == 1)
	<script src="{{ url()->asset('assets/plugins/lazysizes/lazysizes.min.js') }}" async=""></script>
@endif
@if (file_exists(public_path() . '/assets/plugins/select2/js/i18n/'.config('app.locale').'.js'))
	<script src="{{ url()->asset('assets/plugins/select2/js/i18n/'.config('app.locale').'.js') }}" defer></script>
@endif
@if (config('plugins.detectadsblocker.installed'))
	<script src="{{ url('assets/detectadsblocker/js/script.js') . getPictureVersion() }}"defer></script>
@endif

<script>

	function updateLocale(url){
		$.ajax({
			method: 'get',
			"headers": {
				"Content-Type": "application/json",
				"Accept": "application/json",
			},
			url: url,

		}).then(function (data) {
			console.log(data);
			if (data.status) {
				location.reload();
			}
		});
	}
	$(document).ready(function () {
		{{-- Select Boxes --}}
		$('.selecter').select2({
			language: langLayout.select2,
			dropdownAutoWidth: 'true',
			minimumResultsForSearch: Infinity,
			width: '100%'
		});

		{{-- Searchable Select Boxes --}}
		$('.sselecter').select2({
			language: langLayout.select2,
			dropdownAutoWidth: 'true',
			width: '100%'
		});

		{{-- Social Share --}}
		$('.share').ShareLink({
			title: '{{ addslashes(MetaTag::get('title')) }}',
			text: '{!! addslashes(MetaTag::get('title')) !!}',
			url: '{!! request()->fullUrl() !!}',
			width: 640,
			height: 480
		});

		{{-- popper.js --}}
		$('[data-toggle="popover"]').popover();

		{{-- Modal Login --}}
		@if (isset($errors) and $errors->any())
		@if ($errors->any() and old('quickLoginForm')=='1')
		$('#quickLogin').modal();
		@endif
		@endif
	});
</script>
@section('content')
	@if (isset($siteCountryInfo))
		<div class="h-spacer"></div>
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="alert alert-warning">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
						{!! $siteCountryInfo !!}
					</div>
				</div>
			</div>
		</div>
	@endif

	@yield('content')

	@section('info')
	@show

	@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.auto', 'layouts.inc.advertising.auto'])
	<!-- page content goes here -->
	@push('after_scripts')

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Initialize Intersection Observer
				const imageObserver = new IntersectionObserver((entries, observer) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const img = entry.target;
							// Check if image is not in header/banner
							if (!img.closest('.header') && !img.closest('.banner') && !img.hasAttribute('loading')) {
								img.setAttribute('loading', 'lazy');

								// Add fade-in effect
								img.style.opacity = '0';
								img.style.transition = 'opacity 0.3s';
								requestAnimationFrame(() => {
									img.style.opacity = '1';
								});
							}
							observer.unobserve(img);
						}
					});
				}, {
					rootMargin: '50px 0px', // Start loading 50px before image enters viewport
					threshold: 0.01
				});

				// Select images to observe
				const images = document.querySelectorAll('img:not(.header-img):not(.banner-img):not([loading="eager"])');
				images.forEach(img => {
					// Don't lazy load images above fold
					const rect = img.getBoundingClientRect();
					if (rect.top > window.innerHeight) {
						imageObserver.observe(img);
					}
				});

				// Handle dynamically added images
				const contentObserver = new MutationObserver((mutations) => {
					mutations.forEach((mutation) => {
						mutation.addedNodes.forEach((node) => {
							if (node.nodeName === 'IMG' && !node.hasAttribute('loading')) {
								imageObserver.observe(node);
							}
						});
					});
				});

				contentObserver.observe(document.body, {
					childList: true,
					subtree: true
				});
			});
		</script>
	@endpush

	<script src="resources/views/layouts/load.js" defer></script>
	<script>
		function loadDeferredScript() {
			// script logic here
		}

		window.addEventListener('load', loadDeferredScript);

		// Prefetch all links after the page is loaded
		document.addEventListener('DOMContentLoaded', function() {
			var links = document.querySelectorAll('a');
			links.forEach(function(link) {
				var href = link.getAttribute('href');
				if (href && href !== '#' && !link.hasAttribute('download')) {
					var linkPrefetch = document.createElement('link');
					linkPrefetch.rel = 'prefetch';
					linkPrefetch.href = href;
					document.head.appendChild(linkPrefetch);
				}
			});
		});
	</script>
@endsection

@stack('after_scripts_stack')
@yield('after_scripts')

@if (isset($plugins) and !empty($plugins))
	@foreach($plugins as $plugin)
		@yield($plugin . '_scripts')
	@endforeach
@endif

@if (config('settings.footer.tracking_code'))
	{!! printJs(config('settings.footer.tracking_code')) . "\n" !!}
@endif
</body>
</html>``