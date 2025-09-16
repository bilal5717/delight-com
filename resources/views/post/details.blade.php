@extends('layouts.master')

@section('after_styles')
    <script type="application/ld+json">
        <?php
        $appURL = url('/');
        $pages = \App\Models\Page::orderBy('lft', 'ASC')->get();
        $postDateWithYear = $post->created_at->addYear()->format('d F Y');
        $countryCode = '';
        if (session()->has('country_code')) {
            $countryCode = session('country_code');
        }

        $postCategoryId = $post->category->id;
        $postCategory = \App\Models\Category::where('id', $postCategoryId)->first();
        $relatedCategories = \App\Models\Category::where('parent_id', $postCategory->parent_id)->get();

        $key = 0;

        foreach ($latest->posts as $latestPost)
        {
            $key = $key+1;
            $itemListElement = [];
            $itemListElement['@type'] = 'ListItem';
            $itemListElement['position'] = $key;
            $itemListElement['name'] = $latestPost->title;
            $itemListElement['item'] = url('/?', $latestPost->slug);

            $itemsListElementBreadcrumbs [] = $itemListElement;
        }

        if(isset($featured) and !empty($featured) and $featured->posts->count() > 0)
        {
            foreach ($featured->posts as $featuredPost)
            {
                $key = $key+1;
                $itemListElement = [];
                $itemListElement['@type'] = 'ListItem';
                $itemListElement['position'] = $key;
                $itemListElement['name'] = $featuredPost->title;
                $itemListElement['item'] = url('/?', $featuredPost->slug);

                $itemsListElementBreadcrumbs [] = $itemListElement;
            }
        }

        $itemsListElementBreadcrumbs [] = [
            [
                '@type' => 'ListItem',
                'position' => $key + 1,
                'name' => 'Home',
                'item' => $appURL,
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 2,
                'name' => 'Register',
                'item' => $appURL . '/register',
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 3,
                'name' => 'Login',
                'item' => $appURL . '/login',
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 4,
                'name' => 'Create',
                'item' => $appURL . '/create',
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 5,
                'name' => 'Search',
                'item' => $appURL . '/search?d='. $countryCode,
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 6,
                'name' => 'Contact',
                'item' => $appURL . '/contact',
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 7,
                'name' => 'Sitemap',
                'item' => $appURL . '/sitemap',
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 8,
                'name' => 'Password Reset',
                'item' => $appURL . '/password/reset',
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 9,
                'name' => 'Pricing',
                'item' => $appURL . '/pricing',
            ],
            [
                '@type' => 'ListItem',
                'position' => $key + 10,
                'name' => 'Logout',
                'item' => $appURL . '/logout',
            ],

        ];

        $key = $key+ 10;

        foreach ($pages as $page) {
            $key = $key+1;
            $url = \App\Helpers\UrlGen::page($page, $countryCode);
            $itemListElement = [];
            $itemListElement['@type'] = 'ListItem';
            $itemListElement['position'] = $key;
            $itemListElement['name'] = $page->name;
            $itemListElement['item'] = $url;

            $itemsListElementBreadcrumbs [] = $itemListElement;
        }

        foreach ($relatedCategories as $category) {
            $key = $key+1;

            $itemsListElementBreadcrumbs [] = [
                '@type' => 'ListItem',
                'position' => $key,
                'name' => $category->name,
                'item' => \App\Helpers\UrlGen::category($category),
            ];
        }

        $activeDaysArray = [];
        $openTime = '';
        $closeTime = '';
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        if(isset($user) && !empty($user->working_hours_active)){
            $workingHours = json_decode($user->working_hours, true);
            foreach ($workingHours as $key=>$value)
            {
                if($value['isActive'])
                {
                    $activeDaysArray [] = $days[$key];
                    $openTime = $value['timeFrom'];
                    $closeTime = $value['timeTill'];
                }

            }
        }

        $keywords = "";
        if (!empty($post->keywords)) {
            $keywords = explode(',',$post->keywords);
        } else if (!empty($post->tags)) {
            $keywords = explode(',',$post->tags);
        }

        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => ["Product", "Offer"],
            '@id' => url('/?', $post->slug),
            'name' => $post->title,
            'description' => $post->description,
            'keywords' => $keywords,
            'image' => imgUrl($post->pictures->get(0)->filename, 'medium'),
            'Country' => config('country.name'),
            'workHours' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => $activeDaysArray,
                'opens' => $openTime,
                'closes' => $closeTime
            ],
            'offers' => [
                '@type' => "Offer",
                'url' => url('/?', $post->slug),
                'price' => $post->price ?? '',
                'priceCurrency' => $post->country->currency_code ?? '',
                'priceValidUntil' => $postDateWithYear,
                'availability' => 'InStock',
                'hasMerchantReturnPolicy' => [
                    '@type' => "MerchantReturnPolicy",
                    'name' => "30-day return policy",
                    'url' => $appURL . '/page/terms',
                ],
                'shippingDetails' => [
                    '@type' => "OfferShippingDetails",
                    'shippingRate' => [
                        '@type' => "MonetaryAmount",
                        'MonetaryAmount' => "1.00",
                        'currency' => 'USD',
                    ],
                    'shippingDestination' => [
                        '@type' => "DefinedRegion",
                        'addressCountry' => "Worldwide",
                    ],
                    'deliveryTime' => [
                        '@type' => "ShippingDeliveryTime",
                        'businessDays' => [
                            [
                                '@type' => "QuantitativeValue",
                                'minValue' => 10,
                                'maxValue' => 30,
                                'unitCode' => "DAY",
                                'description' => "International delivery time"
                            ],
                            [
                                '@type' => "QuantitativeValue",
                                'minValue' => 2,
                                'maxValue' => 5,
                                'unitCode' => "DAY",
                                'description' => "Domestic delivery time"
                            ]
                        ]
                    ]
                ]
            ],
            'aggregateRating' => [
                '@type' => "AggregateRating",
                'ratingValue' => 4.5,
                'reviewCount' => 5,
            ],
            'review' => [
                [
                    '@type' => "Review",
                    'author' => [
                        '@type' => "Person",
                        'name' => 'John Smith',
                    ],
                    'datePublished' => \Carbon\Carbon::now(),
                    'reviewBody' => "Great service, highly recommended!",
                    'reviewRating' => [
                        '@type' => "Rating",
                        'ratingValue' => 5,
                        'bestRating' => 5,
                        'worstRating' => 1,
                    ],
                ],
                [
                    '@type' => "Review",
                    'author' => [
                        '@type' => "Person",
                        'name' => 'Jane Smith',
                    ],
                    'datePublished' => \Carbon\Carbon::now(),
                    'reviewBody' => "Satisfactory experience.",
                    'reviewRating' => [
                        '@type' => "Rating",
                        'ratingValue' => 4,
                        'bestRating' => 5,
                        'worstRating' => 1,
                    ],
                ],
            'company' => [
                'seller' => [
                    '@type'=> 'Organization',
                    'name'=> isset($post->user) && isset($post->user->company) ? $post->user->company->name : "",
                ],
                'brand' => [
                    '@type' => 'Brand',
                    'name' => isset($post->user) && isset($post->user->company) ? $post->user->company->name : ""
                ]
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('settings.app.app_name'),
                'logo' => url('images/logo.png'),
            ],
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => $itemsListElementBreadcrumbs
            ],
          ],
        ];
        
        // Convert to JSON
        $json_ld = json_encode($structuredData, JSON_UNESCAPED_UNICODE);
        
        // Output the JSON-LD
        echo $json_ld;
        ?>
        </script>
        @endsection

@section('content')
    <link href="{{url('assets/css/jClocksGMT.css')}}" rel="stylesheet">
    {!! csrf_field() !!}
    <input type="hidden" id="postId" name="post_id" value="{{ $post->id }}">

    @if (Session::has('flash_notification'))
        @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
            <?php $paddingTopExists = true; ?>
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
            <?php Session::forget('flash_notification.message'); ?>
    @endif

    <div class="main-container">

        <?php if (isset($topAdvertising) and !empty($topAdvertising)): ?>
        @includeFirst([
                config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.top',
                'layouts.inc.advertising.top',
            ],
            ['paddingTopExists' => $paddingTopExists ?? false])
            <?php
            $paddingTopExists = false;
        endif;
        ?>

        <div class="container {{ (isset($topAdvertising) and !empty($topAdvertising)) ? 'mt-3' : 'mt-2' }}">
        <div class="floating-notification bg-primary" id="notificationContainer"></div>
            <div class="row">
                <div class="col-md-12">

                    <nav aria-label="breadcrumb" role="navigation" class="pull-left">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="icon-home fa"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('country.name') }}</a></li>
                            @if (isset($catBreadcrumb) && is_array($catBreadcrumb) && count($catBreadcrumb) > 0)
                                @foreach ($catBreadcrumb as $key => $value)
                                    <li class="breadcrumb-item">
                                        <a href="{{ $value->get('url') }}">
                                            {!! $value->get('name') !!}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ \Illuminate\Support\Str::limit($post->title, 70) }}</li>
                        </ol>
                    </nav>

                    <div class="pull-right backtolist">
                        <a href="{{ rawurldecode(url()->previous()) }}"><i class="fa fa-angle-double-left"></i>
                            {{ t('back_to_results') }}</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-lg-9 page-content col-thin-right">
                    <div class="inner inner-box items-details-wrapper pb-0">
                        <h2 class="enable-long-words">
                            <strong>
                                <a href="{{ \App\Helpers\UrlGen::post($post) }}" title="{{ $post->title }}">
                                    {{ $post->title }}
                                </a>
                            </strong>
                            @if (config('settings.single.show_post_types'))
                                @if (isset($post->postType) && !empty($post->postType))
                                    <small class="label label-default adlistingtype">{{ $post->postType->name }}</small>
                                @endif
                            @endif
                            @if ($post->featured == 1 && !empty($post->latestPayment))
                                @if (isset($post->latestPayment->package) && !empty($post->latestPayment->package))
                                    <i class="icon-ok-circled tooltipHere"
                                       style="color: {{ $post->latestPayment->package->ribbon }};" title=""
                                       data-placement="bottom" data-toggle="tooltip"
                                       data-original-title="{{ $post->latestPayment->package->short_name }}"></i>
                                @endif
                            @endif
                        <h2>
                            @if ($company_address)
                                <div class="col-md-3">
                                    <div class="card card-user sidebar-card">
                                        <div class="card-header text-center" style="background: blue;">
                                            <a style="color: lemonchiffon;" href="{{ route('company', ['slug' => $company_address->company->company_slug ? $company_address->company->company_slug : $company_address->company->id, 'tab' => 'profile']) }}">
                                                {{ t('about_company') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </h2>
                        <span class="info-row">
                            @if (!config('settings.single.hide_dates'))
                                <span class="date"{!! config('lang.direction') == 'rtl' ? ' dir="rtl"' : '' !!}>
                                    <i class="icon-clock"></i> {!! $post->created_at_formatted !!}
                                </span>&nbsp;
                            @endif
                            <span class="category"{!! config('lang.direction') == 'rtl' ? ' dir="rtl"' : '' !!}>
                                <i class="icon-folder-circled"></i>
                                {{ !empty($post->category->parent) ? $post->category->parent->name : $post->category->name }}
                            </span>&nbsp;
                            <span class="item-location"{!! config('lang.direction') == 'rtl' ? ' dir="rtl"' : '' !!}>
                                <i class="fas fa-map-marker-alt"></i> {{ $post->city->name }}
                            </span>&nbsp;
                            <span class="category"{!! config('lang.direction') == 'rtl' ? ' dir="rtl"' : '' !!}>
                                <i class="icon-eye-3"></i> {{ \App\Helpers\Number::short($post->visits) }}
                                {{ trans_choice('global.count_views', getPlural($post->visits)) }}
                            </span>
                        </span>

                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                <?php $picturesSlider = 'post.inc.pictures-slider.' . config('settings.single.pictures_slider', 'horizontal-thumb'); ?>
                        @if (view()->exists($picturesSlider))
                            @includeFirst([
                                config('larapen.core.customizedViewPath') . $picturesSlider,
                                $picturesSlider,
                            ])
                        @endif
                                </div>
                            </div>
                        </div>
<!-- New Horizontal Info Box Below Slider -->
<div class="info-box row m-3 mb-3 p-3">
    <!-- Duration Section -->
    <div class="col-md-12">
        <h4 class="info-box-title"><i class="fas fa-clock"></i> {{ t('duration_and_location') }}</h4>
    </div>
    <div class="col-md-6">
        @php
            function formatDuration($minutes) {
                $units = [
                    ['value' => 525600, 'unit' => 'year'],
                    ['value' => 43200, 'unit' => 'month'], 
                    ['value' => 10080, 'unit' => 'week'],
                    ['value' => 1440, 'unit' => 'day'],
                    ['value' => 60, 'unit' => 'hour'],
                    ['value' => 1, 'unit' => 'minute']
                ];
                
                foreach ($units as $unit) {
                    if ($minutes >= $unit['value'] && $minutes % $unit['value'] === 0) {
                        $value = $minutes / $unit['value'];
                        return $value . ' ' . t($unit['unit'] . ($value > 1 ? 's' : ''));
                    }
                }
                return $minutes . ' ' . t('minute') . ($minutes > 1 ? 's' : '');
            }

            $durations = \App\Models\PostDuration::where('post_id', $post->id)
                ->where('is_active', true)
                ->get();

            $shippingAddresses = \App\Models\ShippingAddress::all()->keyBy('id');
        @endphp
        
        @if($durations->count() > 0)
            @if($durations->count() === 1)
                @php
                    $duration = $durations->first();
                    $shippingAddress = $shippingAddresses[$duration->location_id] ?? null;
                    $locationText = $shippingAddress ? ($shippingAddress->address_title ?: $shippingAddress->address) : 'N/A';
                    $formattedDuration = formatDuration($duration->duration_value);
                    $currentLocationId = $duration->location_id;
                    
                @endphp
                <input type="hidden" name="duration_id" value="{{ $duration->id }}" />
                <input type="hidden" id="current-location-id" value="{{ $currentLocationId }}" />
                <div class="duration-info">
                    <p><strong>{{ $duration->duration_title }}</strong></p>
                    <p><i class="fas fa-clock"></i>{{ $formattedDuration }}</p>
                    <p><i class="fas fa-map-marker-alt"></i> {{ $locationText }}</p>
                    <p><strong>{{ t('available') }}:</strong> <span id="selected-availability">{{ $duration->available_units }}</span></p>
                </div>
            @else
                <div class="form-group">
                    <select class="form-control" id="duration-select" name="duration_id">
                        @foreach($durations as $index => $duration)
                            @php
                                $shippingAddress = $shippingAddresses[$duration->location_id] ?? null;
                                $locationText = $shippingAddress ? ($shippingAddress->address_title ?: $shippingAddress->address) : 'N/A';
                                $formattedDuration = formatDuration($duration->duration_value);
                            @endphp
                            <option 
                                value="{{ $duration->id }}"
                                data-location-id="{{ $duration->location_id }}"
                                data-location-text="{{ $locationText }}"
                                data-available="{{ $duration->available_units }}"
                                data-max="{{ $duration->max_capacity }}"
                                data-duration-text="{{ $formattedDuration }}"
                                {{ $index === 0 ? 'selected' : '' }}>
                                {{ $duration->duration_title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="duration-details" class="duration-details mt-2">
                    <p><i class="fas fa-clock"></i> <span id="selected-duration-text"></span></p>
                    <p><i class="fas fa-map-marker-alt"></i> <span id="selected-location-text"></span></p>
                    <p><i class="fas fa-users"></i> <span id="selected-availability"></span> {{ t('available') }}</p>
                </div>
            @endif
        @else
            <p>{{ t('No durations available') }}</p>
        @endif
    </div>
    
    <!-- Time Range Section -->
    <div class="col-md-6" id="time-slots-container">
    @php
    use App\Models\Post;
    $post = Post::find($post->id); // Or replace $post->id with the actual ID
    $timeRange = json_decode($post->time_range, true);
    $slots = collect($timeRange['slots'] ?? []);
    $bufferTime = $post->buffer_time ?? 0;
    $currentLocationId = $durations->count() > 0 ? $durations->first()->location_id : null;
@endphp


        @if ($slots->isNotEmpty())
            <div class="time-slots">
                @foreach ($slots as $slot)
                    @php 
                        $timeRanges = $slot['time_ranges'] ?? [];
                        $filteredRanges = [];
                        
                        foreach ($timeRanges as $time) {
                            if (empty($time['location_id'])) {
                                $filteredRanges[] = $time;
                            } elseif (isset($currentLocationId) && is_array($time['location_id']) && in_array($currentLocationId, $time['location_id'])) {
                                $filteredRanges[] = $time;
                            }
                        }
                    @endphp
                    @if (!($slot['disabled'] ?? false) && !empty($filteredRanges))
                        <div class="time-slot-day mb-2">
                            <strong>{{ $slot['day'] }}:</strong>
                            <div class="time-ranges d-flex flex-wrap">
                                @foreach ($filteredRanges as $index => $time)
                                    @php 
                                        $openTime = \Carbon\Carbon::parse($time['open_time']);
                                        $closeTime = \Carbon\Carbon::parse($time['close_time'])->addMinutes($bufferTime);
                                        $timeId = 'time-range-' . $slot['day'] . '-' . $index;
                                    @endphp
                                    <div class="time-range-box m-1 p-1 border rounded" 
                                        id="{{ $timeId }}"
                                        data-day="{{ $slot['day'] }}"
                                        data-open="{{ $openTime->format('H:i') }}"
                                        data-close="{{ $closeTime->format('H:i') }}">
                                        {{ $openTime->format('H:i') }} - {{ $closeTime->format('H:i') }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p>{{ t('No available time slots') }}</p>
        @endif
    </div>

    <style>
    .time-range-box {
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #f8f9fa;
    }
    .time-range-box:hover {
        background-color: #e9ecef;
    }
    .time-range-box.selected {
        background-color: #d1e7ff;
        border: 2px solid #0d6efd !important;
        font-weight: bold;
    }
    </style>

    <div class="col-md-6">
        @php 
            $dateRange = $post->date_range ? json_decode($post->date_range, true) : [];
            $startDate = $dateRange['startDate'] ?? null;
            $endDate = $dateRange['endRepeatOption']['endDate'] ?? null;
        @endphp
        @if ($startDate && $endDate)
            <div class="detail-line d-flex align-items-center justify-content mt-3">
                <h3>{{ t('Date Range') }}:</h3>
                <h5 class="mt-1 mx-2">{{ $startDate }} to {{ $endDate }}</h5>
            </div>
        @endif
    </div>

    @php
        $isOwner = auth()->check() && auth()->id() === $post->user_id;
        // Determine initial visibility for buttons
        $showButtons = false;
        if ($durations->count() === 1) {
            $showButtons = $durations->first()->available_units > 0;
        } elseif ($durations->count() > 1) {
            // Check the initially selected duration (first option)
            $showButtons = $durations->first()->available_units > 0;
        }
    @endphp
    <div class="col-md-12 d-flex align-items-center justify-content-between p-0">
        <!-- Dynamic Total Price Display -->
        <div class="d-flex align-items-center detail-line-lite">
            <span class="fw-bold">
                <strong> {{ t('Total Price:') }} </strong>
            </span>
            <span class="ms-3 text-danger fw-bold" id="dynamic-total-price">
                @if (is_numeric($post->price) && $post->price > 0)
                    {!! \App\Helpers\Number::money($post->price) !!}
                @elseif(is_numeric($post->price) && $post->price == 0)
                    {!! t('free_as_price') !!}
                @else
                    {!! \App\Helpers\Number::money(' --') !!}
                @endif
            </span>
        </div>
        @php
            $bookingStatus = \App\Models\Post::where('id', $post->id)
                ->value('booking_required');
        @endphp
        @if ($bookingStatus == '1')
            <div class="d-flex align-items-center">
                <button id="add-to-cart" class="btn btn-primary btn-lg" 
                    data-post-id="{{ $post->id }}"  
                    @if($isOwner) title="{{ t('Click to add this item to your cart') }}" @endif
                    style="display: {{ $showButtons ? 'inline-block' : 'none' }};">
                    <i class="fas fa-cart-plus"></i> {{ t('Add to Cart') }}
                </button>
                
                @if(isset($AllActiveAddons) && $AllActiveAddons->count() > 0)
                <button id="addons-button" class="btn btn-outline-danger mx-1 btn-lg ms-2 toggle-btn" 
                    @if($isOwner) title="{{ t('addons') }}" @endif 
                    data-toggle="false"
                    style="display: {{ $showButtons ? 'inline-block' : 'none' }};">
                    <input type="hidden" id="toggleState" value="false">
                    <i class="fas fa-plus-circle"></i> {{ t('addons') }}
                </button>
                @endif
            </div>
        @endif
    </div>

    @if($durations->count() > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const durationSelect = document.getElementById('duration-select');
            const addToCartButton = document.getElementById('add-to-cart');
            const addonsButton = document.getElementById('addons-button');
            const selectedAvailability = document.getElementById('selected-availability');
            const selectedDurationText = document.getElementById('selected-duration-text');
            const selectedLocationText = document.getElementById('selected-location-text');

            function updateButtonsVisibility() {
                if (!durationSelect) return;

                const selectedOption = durationSelect.options[durationSelect.selectedIndex];
                const availableUnits = parseInt(selectedOption.dataset.available);
                const durationText = selectedOption.dataset.durationText;
                const locationText = selectedOption.dataset.locationText;

                // Update availability display
                if (selectedAvailability) {
                    selectedAvailability.textContent = availableUnits;
                }
                if (selectedDurationText) {
                    selectedDurationText.textContent = durationText;
                }
                if (selectedLocationText) {
                    selectedLocationText.textContent = locationText;
                }

                // Toggle both buttons' visibility
                const displayStyle = availableUnits > 0 ? 'inline-block' : 'none';
                if (addToCartButton) {
                    addToCartButton.style.display = displayStyle;
                }
                if (addonsButton) {
                    addonsButton.style.display = displayStyle;
                }
            }

            // Initial update
            updateButtonsVisibility();

            // Update on duration change
            if (durationSelect) {
                durationSelect.addEventListener('change', updateButtonsVisibility);
            }
        });
    </script>
    @endif
</div>
               
                        @if (config('plugins.reviews.installed'))
                            @if (view()->exists('reviews::ratings-single'))
                                @include('reviews::ratings-single')
                            @endif
                        @endif


                        <div class="items-details p-3">
                            <ul class="nav nav-tabs" id="itemsDetailsTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="item-details-tab" data-toggle="tab" href="#item-details"
                                       role="tab" aria-controls="item-details" aria-selected="true">
                                        <h4>{{ t('ad_details') }}</h4>
                                    </a>
                                </li>
                                @if (config('plugins.reviews.installed'))
                                    <li class="nav-item">
                                        <a class="nav-link" id="item-{{ config('plugins.reviews.name') }}-tab"
                                           data-toggle="tab" href="#item-{{ config('plugins.reviews.name') }}"
                                           role="tab" aria-controls="item-{{ config('plugins.reviews.name') }}"
                                           aria-selected="false">
                                            <h4>
                                                {{ t('Reviews') }}
                                                @if (isset($rvPost) && !empty($rvPost))
                                                    ({{ $rvPost->rating_count }})
                                                @endif
                                            </h4>
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" id="item-types-tab" data-toggle="tab" href="#item-types"
                                       role="tab" aria-controls="item-types" aria-selected="false">
                                        <h4>{{ $post->productType->name ?? ''}}</h4>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3 mb-3" id="itemsDetailsTabsContent">
                                <div class="tab-pane show active" id="item-details" role="tabpanel"
                                     aria-labelledby="item-details-tab">
                                    <div class="row">
                                        <div
                                                class="items-details-info col-md-12 col-sm-12 col-xs-12 enable-long-words from-wysiwyg">

                                            <div class="row">
                                                <!-- Location -->
                                                <div class="detail-line-lite col-md-6 col-sm-6 col-xs-6">
                                                    <div>
                                                        <span><i class="fas fa-map-marker-alt"></i> {{ t('location') }}:
                                                        </span>
                                                        <span>
                                                            <a href="{!! \App\Helpers\UrlGen::city($post->city) !!}">
                                                                {{ $post->city->name }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>

                                                @if (!in_array($post->category->type, ['not-salable']))
                                                    <!-- Price / Salary -->
                                                    <div class="detail-line-lite col-md-6 col-sm-6 col-xs-6">
                                                        <div>
                                                            <span>
                                                                {{ !in_array($post->category->type, ['job-offer', 'job-search']) ? t('price') : t('Salary') }}:
                                                            </span>
                                                            <span>
                                                                @if (is_numeric($post->price) && $post->price > 0)
                                                                    {!! \App\Helpers\Number::money($post->price) !!}
                                                                @elseif(is_numeric($post->price) && $post->price == 0)
                                                                    {!! t('free_as_price') !!}
                                                                @else
                                                                    {!! \App\Helpers\Number::money(' --') !!}
                                                                @endif
                                                                @if ($post->negotiable == 1)
                                                                    <small class="label badge-success">
                                                                        {{ t('negotiable') }}</small>
                                                                @endif
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endif

                                               
                                            </div>
                                            <hr>

                                            <!-- Description -->
                                            <div class="row">
                                                <div class="col-12 detail-line-content">
                                                    {!! transformDescription($post->description) !!}
                                                </div>
                                            </div>


                                            <!-- Custom Fields -->
                                            @includeFirst([
                                                config('larapen.core.customizedViewPath') .
                                                'post.inc.fields-values',
                                                'post.inc.fields-values',
                                            ])

                                            <!-- Tags -->
                                            @if (!empty($post->tags))
                                                    <?php $tags = array_map('trim', explode(',', $post->tags)); ?>
                                                @if (!empty($tags))
                                                    <div class="row">
                                                        <div class="tags col-12">
                                                            <h4><i class="icon-tag"></i> {{ t('Tags') }}:</h4>
                                                            @foreach ($tags as $iTag)
                                                                <a href="{{ \App\Helpers\UrlGen::tag($iTag) }}">
                                                                    {{ $iTag }}
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            <!-- Actions -->
                                            <div class="row detail-line-action text-center">
                                                <div class="col-4">
                                                    @if (auth()->check())
                                                        @if (auth()->user()->id == $post->user_id)
                                                            <a href="{{ url('posts/' . $post->id . '/edit') }}">
                                                                <i class="icon-pencil-circled tooltipHere"
                                                                   data-toggle="tooltip"
                                                                   data-original-title="{{ t('Edit') }}"></i>
                                                            </a>
                                                        @else
                                                            {!! genEmailContactBtn($post, false, true) !!}
                                                        @endif
                                                    @else
                                                        {!! genEmailContactBtn($post, false, true) !!}
                                                    @endif
                                                </div>
                                                @if (isVerifiedPost($post))
                                                    <div class="col-4">
                                                        <a class="make-favorite" id="{{ $post->id }}"
                                                           href="javascript:void(0)">
                                                            @if (auth()->check())
                                                                @if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
                                                                    <i class="fa fa-heart tooltipHere"
                                                                       data-toggle="tooltip"
                                                                       data-original-title="{{ t('Remove favorite') }}"></i>
                                                                @else
                                                                    <i class="far fa-heart" class="tooltipHere"
                                                                       data-toggle="tooltip"
                                                                       data-original-title="{{ t('Save ad') }}"></i>
                                                                @endif
                                                            @else
                                                                <i class="far fa-heart" class="tooltipHere"
                                                                   data-toggle="tooltip"
                                                                   data-original-title="{{ t('Save ad') }}"></i>
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="{{ url('posts/' . $post->id . '/report') }}">
                                                            <i class="fa icon-info-circled-alt tooltipHere"
                                                               data-toggle="tooltip"
                                                               data-original-title="{{ t('Report abuse') }}"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <br>&nbsp;<br>
                                    </div>
                                </div>

                                @if (config('plugins.reviews.installed'))
                                    @if (view()->exists('reviews::comments'))
                                        @include('reviews::comments')
                                    @endif
                                @endif


                                <div class="tab-pane" id="item-types" role="tabpanel"
                                     aria-labelledby="item-types-tab">
                                    <div class="row">
                                        <!-- Location -->
                                        <div class="type-line-lite col-md-12 col-sm-12 col-xs-12">
                                            <div>
                                                <span>
                                                    {{ $post->productType->description ?? ''}}
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- /.tab content -->

                            <div class="content-footer text-left">
                                @if (auth()->check())
                                    @if (auth()->user()->id == $post->user_id)
                                        <a class="btn btn-default" href="{{ \App\Helpers\UrlGen::editPost($post) }}"><i
                                                    class="fa fa-pencil-square-o"></i> {{ t('Edit') }}</a>
                                    @else
                                        {!! genPhoneNumberBtn($post) !!}
                                        {!! genEmailContactBtn($post) !!}
                                    @endif
                                @else
                                    {!! genPhoneNumberBtn($post) !!}
                                    {!! genEmailContactBtn($post) !!}
                                @endif
                                @includeFirst([
                                    config('larapen.core.customizedViewPath') .
                                    'post.inc.chat-with-other-chat-apps',
                                    'post.inc.chat-with-other-chat-apps',
                                ])
                            </div>
                        </div>
                    </div>
                    <!--/.items-details-wrapper-->
                </div>
                
                <!-- side bar for add to cart -->
                <div class="col-lg-3 page-sidebar-right d-none" id="addons-sidebar">
    <aside>
        <div class="card sidebar-card shadow-lg">
            <div class="card-header">{{ t('Customize Your Plan.') }}</div>
            <div class="card-content">
                <div class="card-body">
                    @if(isset($AllActiveAddons) && $AllActiveAddons->count() > 0)
                        <ul class="list-unstyled addon-list">
                            @foreach($AllActiveAddons as $addon)
                                <li class="addon-item mb-3 p-3 border rounded">
                                    <div class="form-check">
                                        <input
                                            type="checkbox"
                                            class="form-check-input addon-checkbox"
                                            id="addon-{{ $addon->id }}"
                                            data-price="{{ $addon->amount }}"
                                            data-title="{{ $addon->title }}"
                                        />
                                        <label class="form-check-label d-flex justify-content-between align-items-center" for="addon-{{ $addon->id }}">
                                            <span>
                                                <strong>{{ $addon->title }}</strong>
                                                <small class="d-block text-muted">{{ $addon->description }}</small>
                                            </span>
                                            <span class="badge bg-success">+${{ number_format($addon->amount, 2) }}</span>
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <hr class="my-4">

                        <div class="price-summary">
                            <h5 class="d-flex justify-content-between align-items-center">
                                <span>{{ t('Base Price:') }}</span>
                                <span class="text-primary">$<span id="basePrice">{{ number_format($post->price, 2) }}</span></span>
                            </h5>
                            <h5 class="d-flex justify-content-between align-items-center">
                                <span>{{ t('Addons:') }}</span>
                                <span class="text-success">+$<span id="addonsPrice">0.00</span></span>
                            </h5>
                            <hr>
                            <h4 class="d-flex justify-content-between align-items-center">
                                <span>{{ t('Total Price:') }}</span>
                                <span class="text-danger">$<span id="totalPrice">{{ number_format($post->price, 2) }}</span></span>
                            </h4>
                        </div>

                        @php
                            $isOwner = auth()->check() && auth()->id() === $post->user_id;
                        @endphp

                       
                        <div class="d-grid gap-2 mt-4">
                            <button class="btn btn-primary btn-block mt-2" id="previewPlan" @if($isOwner) title="{{ t('Preview Plan') }}" @endif>{{ t('Preview Plan') }}</button>
                            <button class="btn btn-primary btn-block mt-2 addtoCart" id="addtoCart" @if($isOwner) title="{{ t('Click to add this item to your cart') }}" @endif>{{ t('Add to Cart with Addons') }}</button>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">{{ t('No add-ons available.') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </aside>
   
</div>
<style>
    .info-box {
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    
    .info-box-title {
        font-size: 1.1rem;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }
    
    .duration-info p,
    .time-slot-day,
    .date-range p {
        margin-bottom: 5px;
        font-size: 0.9rem;
    }
    
    .time-ranges {
        display: flex;
        flex-wrap: wrap;
        margin-top: 5px;
    }
    
    .service-type-value {
        font-size: 1rem;
        color: #555;
        padding: 8px;
        background-color: #f0f0f0;
        border-radius: 4px;
    }
    
    .description-content {
        padding: 10px;
        background-color: #f9f9f9;
        border-radius: 4px;
        border: 1px solid #eee;
    }
    
    .price-display {
        font-size: 1.5rem;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
    }

#dynamic-total-price {
    font-size: 1.1em;
    white-space: nowrap;
}

@media (max-width: 768px) {
    #dynamic-total-price {
        font-size: 1em;
    }
}
    .action-buttons {
        margin-top: 20px;
    }
    
    @media (max-width: 768px) {
        .info-box .col-md-6 {
            margin-bottom: 15px;
        }
        
        .action-buttons .btn {
            width: 100%;
            margin-bottom: 10px;
            margin-left: 0 !important;
        }
    }
</style>

                <div class="col-lg-3 page-sidebar-right " id="default-sidebar">
                    <aside>
                        @php
                            $new = \App\Models\User::where('id', $post->user_id)->first();
                            $user_status_id = $new ? $new->user_status_id : null;
                            $user_status = $user_status_id ? \App\Models\UserStatus::find($user_status_id) : null;
                            @endphp
                        <div class="card card-user-info sidebar-card">
                            @if (!auth()->check() && auth()->check() && auth()->id() == $post->user_id)
                                <div class="card-header">{{ t('Manage Ad') }}</div>
                            @else
                                <div class="block-cell user">
                                   <div class="cell-media">
    @if ($user_status && $user_status->icon)
        <img alt="{{ $user_status->title }}" 
             class="img-fluid rounded" 
             width="150px"
             src="{{ asset('storage/user_status_icons/' . $user_status->icon) }}"
             data-toggle="tooltip" 
             data-placement="top" 
             title="{{ $user_status->title }}">
    @elseif ($company_address)
        <img alt="Logo" 
             class="img-fluid rounded" 
             width="150px"
             src="{{ asset('storage/' . $company_address->company->logo) }}"
             @if($user_status && $user_status->title)
                 data-toggle="tooltip" 
                 data-placement="top" 
                 title="{{ $user_status->title }}"
             @endif>
    @else
        <img src="{{ $post->user_photo_url }}" 
             alt="{{ $post->contact_name }}"
             @if($user_status && $user_status->title)
                 data-toggle="tooltip" 
                 data-placement="top" 
                 title="{{ $user_status->title }}"
             @endif>
    @endif
</div>
                                    <div class="cell-content">
                                        <h5 class="title">{{ t('Posted by') }}</h5>
                                        <span class="name">
                                            @if ($company_address)
                                                <a href="{{ \App\Helpers\UrlGen::user($user) }}">
                                                    {{ $company_address ? $company_address->company->name : '' }}
                                                </a>
                                            @else
                                                @if (isset($user) && !empty($user))
                                                    <a href="{{ \App\Helpers\UrlGen::user($user) }}">
                                                        {{ $post->contact_name }}
                                                    </a>
                                                @else
                                                    {{ $post->contact_name }}
                                                @endif
                                            @endif
                                        </span>

                                        @if (config('plugins.reviews.installed'))
                                            @if (view()->exists('reviews::ratings-user'))
                                                @include('reviews::ratings-user')
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            @endif

                            <div class="card-content">
                                <?php $evActionStyle = 'style="border-top: 0;"'; ?>
                                @if (!auth()->check() ||
                                    (auth()->check() &&
                                        auth()->user()->getAuthIdentifier() != $post->user_id))
                                    <div class="card-body text-left">
                                        <div class="grid-col">
                                            <div class="col from">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ t('location') }}</span>
                                            </div>
                                            <div class="col to">
                                                <span>
                                                    <a href="{!! \App\Helpers\UrlGen::city($post->city) !!}">
                                                        {{ $post->city->name }}
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                        @if (!config('settings.single.hide_dates'))
                                            @if (isset($user) && !empty($user) && !is_null($user->created_at_formatted))
                                                <div class="grid-col">
                                                    <div class="col from">
                                                        <i class="fas fa-user"></i>
                                                        <span>{{ t('Joined') }}</span>
                                                    </div>
                                                    <div class="col to">
                                                        <span>{!! $user->created_at_formatted !!}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                        <?php $evActionStyle = 'style="border-top: 1px solid #ddd;"'; ?>
                                @endif

                                <div class="ev-action" {!! $evActionStyle !!}>
                                    @if (auth()->check())
                                        @if (auth()->user()->id == $post->user_id)
                                            <a href="{{ \App\Helpers\UrlGen::editPost($post) }}"
                                               class="btn btn-default btn-block">
                                                <i class="fa fa-pencil-square-o"></i> {{ t('Update the Details') }}
                                            </a>
                                            @if (config('settings.single.publication_form_type') == '1')
                                                <a href="{{ url('posts/' . $post->id . '/photos') }}"
                                                   class="btn btn-default btn-block">
                                                    <i class="icon-camera-1"></i> {{ t('Update Photos') }}
                                                </a>
                                                @if (isset($countPackages) && isset($countPaymentMethods) && $countPackages > 0 && $countPaymentMethods > 0)
                                                    <a href="{{ url('posts/' . $post->id . '/payment') }}"
                                                       class="btn btn-success btn-block">
                                                        <i class="icon-ok-circled2"></i> {{ t('Make It Premium') }}
                                                    </a>
                                                @endif
                                            @endif
                                        @else
                                            {!! genPhoneNumberBtn($post, true) !!}
                                            {!! genEmailContactBtn($post, true) !!}
                                            @includeFirst([
                                                    config('larapen.core.customizedViewPath') .
                                                    'post.inc.chat-with-other-chat-apps',
                                                    'post.inc.chat-with-other-chat-apps',
                                                ],
                                                ['isSidebar' => true])
                                        @endif
                                            <?php
                                            try {
                                                if (
                                                    auth()
                                                        ->user()
                                                        ->can(\App\Models\Permission::getStaffPermissions())
                                                ) {
                                                    $btnUrl = admin_url('blacklists/add') . '?email=' . $post->email;

                                                    if (!isDemo($btnUrl)) {
                                                        $cMsg = trans('admin.confirm_this_action');
                                                        $cLink = "window.location.replace('" . $btnUrl . "'); window.location.href = '" . $btnUrl . "';";
                                                        $cHref = "javascript: if (confirm('" . addcslashes($cMsg, "'") . "')) { " . $cLink . " } else { void('') }; void('')";

                                                        $btnText = trans('admin.ban_the_user');
                                                        $btnHint = trans('admin.ban_the_user_email', ['email' => $post->email]);
                                                        $tooltip = ' data-toggle="tooltip" data-placement="bottom" title="' . $btnHint . '"';

                                                        $btnOut = '';
                                                        $btnOut .= '<a href="' . $cHref . '" class="btn btn-danger btn-block"' . $tooltip . '>';
                                                        $btnOut .= $btnText;
                                                        $btnOut .= '</a>';

                                                        echo $btnOut;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                            }
                                            ?>
                                    @else
                                        {!! genPhoneNumberBtn($post, true) !!}
                                        {!! genEmailContactBtn($post, true) !!}
                                        @includeFirst([
                                                config('larapen.core.customizedViewPath') .
                                                'post.inc.chat-with-other-chat-apps',
                                                'post.inc.chat-with-other-chat-apps',
                                            ],
                                            ['isSidebar' => true])
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($company_address)
                            <div class="card card-user-info sidebar-card">
                                <div class="card-header text-center">{{ t('company_information') }}</div>
                                <div class="card-content user-info">
                                    <div class="card-body text-center">
                                        <div class="company-info">
                                            <div class="company-logo-thumb mb20">
                                                <a href="#">
                                                    <img alt="Logo" class="img-fluid rounded" width="150px"
                                                         src="{{ asset('storage/' . $company_address->company->logo) }}">
                                                </a>
                                            </div>
                                            <strong>
                                                @if (auth()->check() && auth()->user()->id == $company_address->company->user_id)
                                                    <a href="{{ route('company-profile') }}">
                                                        {{ $company_address ? $company_address->company->name : '' }}
                                                    </a>
                                                @else
                                                    <a
                                                            href="{{ route('company', ['slug' => $company_address->company->company_slug ? $company_address->company->company_slug : $company_address->company->id, 'tab' => 'profile'] ) }}">
                                                        {{ $company_address ? $company_address->company->name : '' }}
                                                    </a>
                                                @endif
                                            </strong>

                                            <p>
                                                {{ trans('company.address') }}:&nbsp;
                                                <strong>
                                                    <a
                                                            href="{{ url('https://www.google.com/maps/place/' . $company_address->address) }}">
                                                        {{ $company_address->address }}
                                                    </a>
                                                </strong>
                                            </p>
                                            <p>
                                                {{ trans('company.company_city') }}:&nbsp;
                                                <strong>
                                                    <a
                                                            href="{{ url('https://www.google.com/maps/place/' . $company_address->city->name) }}">
                                                        {{ $company_address->city->name }}
                                                    </a>
                                                </strong>
                                            </p>
                                            <p>
                                                {{ trans('company.company_country') }}:&nbsp;
                                                <strong>
                                                    <a href="#">
                                                        {{ $company_address->city->country->name }}
                                                    </a>
                                                </strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($company_address)
                            <div class="card card-user-info sidebar-card">
                                <div class="card-header text-center">
                                    <a href="{{ route('company', ['slug' => $company_address->company->company_slug ? $company_address->company->company_slug : $company_address->company->id, 'tab' => 'profile']) }}">
                                        {{ t('about_company') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="row justify-content-center">
                            <div class="center" id="clock_seller"></div>
                        </div>
                        <div class="alert alert-danger" role="alert" id="working-hours-div" style="display: none;">

                        </div>

                        @if (config('settings.single.show_post_on_googlemap'))
                            <div class="card sidebar-card">
                                <div class="card-header">{{ t('location_map') }}</div>
                                <div class="card-content">
                                    <div class="card-body text-left p-0">
                                        <div class="ads-googlemaps">
                                            <iframe id="googleMaps" width="100%" height="250" frameborder="0"
                                                    scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (isVerifiedPost($post))
                            @includeFirst([
                                config('larapen.core.customizedViewPath') . 'layouts.inc.social.horizontal',
                                'layouts.inc.social.horizontal',
                            ])
                        @endif

                        <div class="card sidebar-card">
                            <div class="card-header">{{ t('Safety Tips for Buyers') }}</div>
                            <div class="card-content">
                                <div class="card-body text-left">
                                    <ul class="list-check">
                                        <li> {{ t('Meet seller at a public place') }} </li>
                                        <li> {{ t('Check the item before you buy') }} </li>
                                        <li> {{ t('Pay only after collecting the item') }} </li>
                                    </ul>
                                    <?php $tipsLinkAttributes = getUrlPageByType('tips'); ?>
                                    @if (!\Illuminate\Support\Str::contains($tipsLinkAttributes, 'href="#"') &&
                                        !\Illuminate\Support\Str::contains($tipsLinkAttributes, 'href=""'))
                                        <p>
                                            <a class="pull-right" {!! $tipsLinkAttributes !!}>
                                                {{ t('Know more') }}
                                                <i class="fa fa-angle-double-right"></i>
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card sidebar-card">
                            <div class="card-header text-center">{{ t('Similar posts') }}</div>
                            <div class="card-content">
                                {{-- @foreach ($latest->posts as $post)
                                <div class="card-body text-left">
                                    <div class="company-logo-thumb mb20 bg-light p-1 text-center">
                                        @if ($post->user->photo)
                                            <img class="rounded border border-secondary  p-1  w-75"
                                                src="{{ asset('storage/' . $post->user->photo) }}" alt=""
                                                height="100px">
                                        @else
                                            <img class="rounded border border-secondary  p-1  w-75"
                                                src="{{ asset('storage/app/default/picture.jpg') }}" alt=""
                                                height="100px">
                                        @endif
                                    </div>
                                    <p class="text-center p-1 m-0 border-bottom">
                                        <strong>
                                            {{ $post->title }}
                                        </strong>
                                    </p>
                                    <p class="m-0 p-0">
                                        <strong class="text-primary">Description : </strong>
                                        <strong>
                                            {!! strip_tags(Str::limit($post->description, 90)) !!}
                                        </strong>
                                    </p>
                                    <p class="m-0 p-0">
                                        <strong class="text-primary">Price : </strong>
                                        <strong>
                                            {{ $post->price }}
                                        </strong>
                                    </p>
                                    <p class="m-0 p-0">
                                        <strong class="text-primary">Location : </strong>
                                        <strong>
                                            {{ $post->city->name }}
                                        </strong>
                                    </p>
                                </div>
                                @endforeach --}}
                                <div class="card-body text-left">
                                    @foreach ($latest->posts as $key => $latestPost)
                                        @continue(empty($latestPost->city))
                                            <?php
                                            // Main Picture
                                            if ($latestPost->pictures->count() > 0) {
                                                $postImg = imgUrl($latestPost->pictures->get(0)->filename, 'medium');
                                            } else {
                                                $postImg = imgUrl(config('larapen.core.picture.default'), 'medium');
                                            }
                                            ?>
                                        <div class="item-list">
                                            @if ($latestPost->featured == 1)
                                                @if (isset($latestPost->latestPayment, $latestPost->latestPayment->package) && !empty($latestPost->latestPayment->package))
                                                    @if ($latestPost->latestPayment->package->ribbon != '')
                                                        <div
                                                                class="cornerRibbons {{ $latestPost->latestPayment->package->ribbon }}">
                                                            <a href="#">
                                                                {{ $latestPost->latestPayment->package->short_name }}</a>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif

                                            <div class="row">
                                                <div class="col no-padding photobox">
                                                    <div class="add-image">

                                                        <a href="{{ \App\Helpers\UrlGen::post($latestPost) }}">
                                                            <img class="lazyload img-thumbnail no-margin"
                                                                 src="{{ $postImg }}" alt="{{ $latestPost->title }}">
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="add-desc-box">
                                                    <div class="items-details">
                                                        <h5 class="add-title text-center">
                                                            <a
                                                                    href="{{ \App\Helpers\UrlGen::post($latestPost) }}">{{ \Illuminate\Support\Str::limit($latestPost->title, 70) }}</a>
                                                        </h5>

                                                        <span class="info-row">
                                                            @if (config('settings.single.show_post_types'))
                                                                @if (isset($latestPost->postType) && !empty($latestPost->postType))
                                                                    <span class="add-type business-ads tooltipHere"
                                                                          data-toggle="tooltip" data-placement="bottom"
                                                                          title="{{ $latestPost->postType->name }}">
                                                                        {{ strtoupper(mb_substr($latestPost->postType->name, 0, 1)) }}
                                                                    </span>&nbsp;
                                                                @endif
                                                            @endif
                                                            @if (!config('settings.listing.hide_dates'))
                                                                <span class="date">
                                                                    <i class="icon-clock"></i> {!! $latestPost->created_at_formatted !!}
                                                                </span>
                                                            @endif
                                                            <span class="category"{!! config('lang.direction') == 'rtl' ? ' dir="rtl"' : '' !!}>
                                                                <i class="icon-folder-circled"></i>&nbsp;
                                                                @if (isset($latestPost->category->parent) && !empty($latestPost->category->parent))
                                                                    <a href="{!! \App\Helpers\UrlGen::category($latestPost->category->parent) !!}" class="info-link">
                                                                        {{ $latestPost->category->parent->name }}
                                                                    </a>&nbsp;&raquo;&nbsp;
                                                                @endif
                                                                <a href="{!! \App\Helpers\UrlGen::category($latestPost->category) !!}" class="info-link">
                                                                    {{ $latestPost->category->name }}
                                                                </a>
                                                            </span>
                                                            <span class="item-location"{!! config('lang.direction') == 'rtl' ? ' dir="rtl"' : '' !!}>
                                                                <i class="icon-location-2"></i>&nbsp;
                                                                <a href="{!! \App\Helpers\UrlGen::city($latestPost->city) !!}" class="info-link">
                                                                    {{ $latestPost->city->name }}
                                                                </a>
                                                                {{ isset($latestPost->distance) ? '- ' . round($latestPost->distance, 2) . getDistanceUnit() : '' }}
                                                            </span>
                                                        </span>
                                                    </div>

                                                    @if (config('plugins.reviews.installed'))
                                                        @if (view()->exists('reviews::ratings-list'))
                                                            @include('reviews::ratings-list')
                                                        @endif
                                                    @endif

                                                </div>

                                                <div class="text-right price-box" style="white-space: nowrap;">
                                                    <h4 class="item-price">
                                                        @if (isset($latestPost->category, $latestPost->category->type))
                                                            @if (!in_array($latestPost->category->type, ['not-salable']))
                                                                @if (is_numeric($latestPost->price) && $latestPost->price > 0)
                                                                    &nbsp; {!! \App\Helpers\Number::money($latestPost->price) !!}
                                                                @elseif(is_numeric($latestPost->price) && $latestPost->price == 0)
                                                                    {!! t('free_as_price') !!}
                                                                @else
                                                                    {!! \App\Helpers\Number::money(' --') !!}
                                                                @endif
                                                            @endif
                                                        @else
                                                            {{ '--' }}
                                                        @endif
                                                    </h4>&nbsp;
                                                    @if (isset($latestPost->latestPayment, $latestPost->latestPayment->package) && !empty($latestPost->latestPayment->package))
                                                        @if ($latestPost->latestPayment->package->has_badge == 1)
                                                            <a class="btn btn-danger btn-sm make-favorite">
                                                                <i class="fa fa-certificate"></i>
                                                                <span> {{ $latestPost->latestPayment->package->short_name }}
                                                                </span>
                                                            </a>&nbsp;
                                                        @endif
                                                    @endif
                                                    @if (isset($latestPost->savedByLoggedUser) && $latestPost->savedByLoggedUser->count() > 0)
                                                        <a class="btn btn-success btn-sm make-favorite"
                                                           style="margin-left: 150px" id="{{ $latestPost->id }}">
                                                            <i class="fa fa-heart"></i><span> {{ t('Saved') }} </span>
                                                        </a>
                                                    @else
                                                        <a class="btn btn-default btn-sm make-favorite"
                                                           id="{{ $latestPost->id }}">
                                                            <i class="fa fa-heart"></i><span> {{ t('Save') }} </span>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div style="clear: both"></div>

                                    @if (isset($latestOptions) &&
                                        isset($latestOptions['show_view_more_btn']) &&
                                        $latestOptions['show_view_more_btn'] == '1')
                                        <div class="mb20 text-center">
                                            <a href="{{ \App\Helpers\UrlGen::search() }}" class="btn btn-default mt10">
                                                <i class="fa fa-arrow-circle-right"></i> {{ t('View more') }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>

        </div>

        @if (config('settings.single.similar_posts') == '1' || config('settings.single.similar_posts') == '2')
            @includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.featured', 'home.inc.featured'],
                ['firstSection' => false])
        @endif

        @includeFirst([
                config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.bottom',
                'layouts.inc.advertising.bottom',
            ],
            ['firstSection' => false])

        @if (isVerifiedPost($post))
            @includeFirst([
                    config('larapen.core.customizedViewPath') . 'layouts.inc.tools.facebook-comments',
                    'layouts.inc.tools.facebook-comments',
                ],
                ['firstSection' => false])
        @endif

    </div>
@endsection

@section('modal_message')
    @if (config('settings.single.show_security_tips') == '1')
        @includeFirst([
            config('larapen.core.customizedViewPath') . 'post.inc.security-tips',
            'post.inc.security-tips',
        ])
    @endif
    @if (auth()->check() || config('settings.single.guests_can_contact_ads_authors') == '1')
        @includeFirst([
            config('larapen.core.customizedViewPath') . 'account.messenger.modal.create',
            'account.messenger.modal.create',
        ])
    @endif
@endsection

@section('after_styles')
    <!-- bxSlider CSS file -->
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url('assets/plugins/bxslider/jquery.bxslider.rtl.css') }}" rel="stylesheet" />
    @else
        <link href="{{ url('assets/plugins/bxslider/jquery.bxslider.css') }}" rel="stylesheet" />
    @endif
@endsection

@section('before_scripts')
    <script>
        var showSecurityTips = '{{ config('settings.single.show_security_tips', '0') }}';
    </script>
@endsection

@section('after_scripts')
    @if (config('services.googlemaps.key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googlemaps.key') }}"
                type="text/javascript" defer></script>
    @endif

    <!-- bxSlider Javascript file -->
    <script src="{{ url('assets/plugins/bxslider/jquery.bxslider.min.js') }}"></script>
    <script src="{{ asset('/assets/js/moment.min.js') }}"></script>

    <script>
        /* Favorites Translation */
        var lang = {
            labelSavePostSave: "{!! t('Save ad') !!}",
            labelSavePostRemove: "{!! t('Remove favorite') !!}",
            loginToSavePost: "{!! t('Please log in to save the Ads') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search') !!}",
            confirmationSavePost: "{!! t('Post saved in favorites successfully') !!}",
            confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully') !!}",
            confirmationSaveSearch: "{!! t('Search saved successfully') !!}",
            sellerHoursText1: "{!! t('seller_hours_text_1') !!}",
            sellerHoursTextPartTwo: "{!! t('seller_hours_text_2') !!}",
            sellerHoursTextPartThree: "{!! t('seller_hours_text_3') !!}",
        };

        $(document).ready(function() {
            $('[rel="tooltip"]').tooltip({
                trigger: "hover"
            });

            @if (config('settings.single.show_post_on_googlemap'))
            /* Google Maps */
            getGoogleMaps(
                '{{ config('services.googlemaps.key') }}',
                '{{ isset($post->city) && !empty($post->city) ? addslashes($post->city->name) . ',' . config('country.name') : config('country.name') }}',
                '{{ config('app.locale') }}'
            );
            @endif

            /* Keep the current tab active with Twitter Bootstrap after a page reload */
            /* For bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line */
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                /* save the latest tab; use cookies if you like 'em better: */
                localStorage.setItem('lastTab', $(this).attr('href'));
            });
            /* Go to the latest tab, if it exists: */
            var lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
                $('[href="' + lastTab + '"]').tab('show');
            }
        });

        offset = getOffset('{{$post->city->time_zone}}')/60;

        var sellerDate = new Date(new Date().toLocaleString('en-US', { timeZone: '{!! $post->city?->time_zone ?? "UTC" !!}'}));
        let weekDayValue = sellerDate.getDay()-1;
        let working_hours_active = '{!! $user?->working_hours_active ?? "" !!}';
        if(working_hours_active === 'active'){
            let working_hour_array = JSON.parse('{!! $user?->working_hours ?? "[]" !!}');
            let today_working_schedule = working_hour_array[weekDayValue];
            if(today_working_schedule['isActive']){
                var currTime = moment(sellerDate.getHours() +':'+ sellerDate.getMinutes(), 'h:mma');
                var startTime = moment(today_working_schedule['timeFrom'], 'h:mma');
                var endTime = moment(today_working_schedule['timeTill'], 'h:mma');

                if(currTime.isBefore(endTime) &&  startTime.isBefore(currTime)){
                    $("#working-hours-div").html('');
                    $("#working-hours-div").hide();
                }
                else if(currTime.isBefore(startTime)){
                    $("#working-hours-div").html(lang['sellerHoursText1'] +', '+ lang['sellerHoursTextPartTwo'] + working_hour_array[weekDayValue]['timeFrom']);
                    $("#working-hours-div").show();
                }
                else if(currTime.isAfter(endTime)){
                    $("#working-hours-div").html(lang['sellerHoursText1'] + getNextWorkingDay(working_hour_array, weekDayValue));
                    $("#working-hours-div").show();
                }
            }
            else {
                $("#working-hours-div").html(lang['sellerHoursText1'] + getNextWorkingDay(working_hour_array, weekDayValue));
                $("#working-hours-div").show();
            }
        }

        function getNextWorkingDay(working_hour_array, currentDay)
        {
            for( i = currentDay+1; i<7; i++)
            {
                var nextDay = working_hour_array[i];
                if(nextDay['isActive'])
                {
                    return ', '+ lang['sellerHoursTextPartThree'] + dayName(i) +' at '+nextDay['timeFrom'];
                }
            }

            for( i = 0; i < currentDay; i++)
            {
                var nextDay = working_hour_array[i];
                if(nextDay['isActive'])
                {
                    return ', '+ lang['sellerHoursTextPartThree'] + dayName(i) +' at '+nextDay['timeFrom'];
                }
            }

            return '';
        }

        function dayName(dayIndex)
        {
            var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

            return dayTranslation(days[dayIndex]);
        }

        function dayTranslation(day)
        {
            var days = {
                Monday: "{!! t('Monday') !!}",
                Tuesday: "{!! t('Tuesday') !!}",
                Wednesday: "{!! t('Wednesday') !!}",
                Thursday: "{!! t('Thursday') !!}",
                Friday: "{!! t('Friday') !!}",
                Saturday: "{!! t('Saturday') !!}",
                Sunday: "{!! t('Sunday') !!}"
            };

            return days[day];
        }

        function getOffset (timeZone = 'UTC', date = new Date())
        {
            const utcDate = new Date(date.toLocaleString('en-US', { timeZone: 'UTC' }));
            const tzDate = new Date(date.toLocaleString('en-US', { timeZone }));
            return (tzDate.getTime() - utcDate.getTime()) / 6e4;
        }
    </script>
    <script src="{{ url('assets/js/jquery.rotate.js') }}"></script>
    <script src="{{ url('assets/js/jClocksGMT.js') }}"></script>
    <script>
        $(function () {
            $('#clock_seller').jClocksGMT({ title: '{{$post->city->name}}', offset: offset, skin: 5 });
        });

        slider = $('.bxslider').bxSlider({
            adaptiveHeight: true,
            mode: 'fade',
            pause: 1000,
            speed: 2000,
            pager: false,
            useCSS: false
        });
        slider.goToSlide(1);
    </script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let selectedTimeSlots = [];
    let selectedDuration = null;
    const basePrice = parseFloat("{{ $post->price }}");
    let currentAddonsTotal = 0;
    let currentTotalPrice = basePrice;
    let timeSlotsMultiplier = 1;

    function filterTimeSlotsByLocation(locationId) {
        const timeRangeData = @json($timeRange);
        const bufferTime = {{ $bufferTime }};
        const container = document.getElementById('time-slots-container');
        console.log(timeRangeData);
        container.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> Loading time slots...</div>';
        
        let html = '<div class="time-slots">';
        
        timeRangeData.slots.forEach(slot => {
            if (slot.disabled || !slot.time_ranges) return;
            
            let dayHtml = '';
            let timeRangesHtml = '';
            
            slot.time_ranges.forEach((time, index) => {
                if (!time.location_id || 
                    (locationId && time.location_id.includes(locationId.toString()))) {
                    
                    const openTime = moment(time.open_time, 'HH:mm');
                    const closeTime = moment(time.close_time, 'HH:mm').add(bufferTime, 'minutes');
                    
                    timeRangesHtml += `
                        <div class="time-range-box m-1 p-1 border rounded" 
                            id="time-range-${slot.day}-${index}"
                            data-day="${slot.day}"
                            data-open="${openTime.format('HH:mm')}"
                            data-close="${closeTime.format('HH:mm')}">
                            ${openTime.format('HH:mm')} - ${closeTime.format('HH:mm')}
                        </div>
                    `;
                }
            });
            
            if (timeRangesHtml) {
                dayHtml = `
                    <div class="time-slot-day mb-2">
                        <strong>${slot.day}:</strong>
                        <div class="time-ranges d-flex flex-wrap">
                            ${timeRangesHtml}
                        </div>
                    </div>
                `;
                html += dayHtml;
            }
        });
        
        html += '</div>';
        
        if (html === '<div class="time-slots"></div>') {
            html = '<p>{{ t("No available time slots for this location") }}</p>';
        }
        
        container.innerHTML = html;
        document.querySelectorAll('.time-range-box').forEach(slot => {
            slot.addEventListener('click', function() {
                this.classList.toggle('selected');
                const day = this.dataset.day;
                const openTime = this.dataset.open;
                const closeTime = this.dataset.close;
                const elementId = this.id;

                const existingIndex = selectedTimeSlots.findIndex(s => 
                    s.day === day && s.openTime === openTime && s.closeTime === closeTime
                );

                if (existingIndex >= 0) {
                    selectedTimeSlots.splice(existingIndex, 1);
                } else {
                    selectedTimeSlots.push({ day, openTime, closeTime, elementId });
                }
                
                updateTotalPrice();
                updateSelectionSummary();
            });
        });
    }
    $('#duration-select').change(function() {
        const selectedOption = $(this).find('option:selected');
        const locationId = selectedOption.data('location-id');
        
        filterTimeSlotsByLocation(locationId);
        
        $('#selected-duration-text').text(selectedOption.data('duration-text'));
        $('#selected-location-text').text(selectedOption.data('location-text'));
        $('#selected-availability').text(selectedOption.data('available') + '/' + selectedOption.data('max'));
        
        selectedDuration = {
            id: selectedOption.val(),
            title: selectedOption.text(),
            duration: selectedOption.data('duration-text'),
            location: selectedOption.data('location-text'),
            available: selectedOption.data('available'),
            maxCapacity: selectedOption.data('max')
        };
        
        updateSelectionSummary();
    });

    if ($('#duration-select').length > 0) {
        const initialLocationId = $('#duration-select option:selected').data('location-id');
        filterTimeSlotsByLocation(initialLocationId);
        
        const selectedOption = $('#duration-select option:selected');
        $('#selected-duration-text').text(selectedOption.data('duration-text'));
        $('#selected-location-text').text(selectedOption.data('location-text'));
        $('#selected-availability').text(selectedOption.data('available') + '/' + selectedOption.data('max'));
        
        selectedDuration = {
            id: selectedOption.val(),
            title: selectedOption.text(),
            duration: selectedOption.data('duration-text'),
            location: selectedOption.data('location-text'),
            available: selectedOption.data('available'),
            maxCapacity: selectedOption.data('max')
        };
    }
    
    if ($('#current-location-id').length > 0) {
        const locationId = $('#current-location-id').val();
        filterTimeSlotsByLocation(locationId);
    }

    function updateTotalPrice() {
        currentAddonsTotal = 0;
        document.querySelectorAll('.addon-checkbox:checked').forEach(checkbox => {
            currentAddonsTotal += parseFloat(checkbox.dataset.price);
        });
        
        timeSlotsMultiplier = selectedTimeSlots.length > 0 ? selectedTimeSlots.length : 1;
        
        currentTotalPrice = (basePrice + currentAddonsTotal) * timeSlotsMultiplier;
        
        const addonsPriceElement = document.getElementById('addonsPrice');
        const totalPriceElement = document.getElementById('totalPrice');
        const dynamicTotalElement = document.getElementById('dynamic-total-price');
        const multiplierElement = document.getElementById('timeSlotsMultiplier');
        
        if (addonsPriceElement) addonsPriceElement.textContent = currentAddonsTotal.toFixed(2);
        if (totalPriceElement) totalPriceElement.textContent = currentTotalPrice.toFixed(2);
        if (dynamicTotalElement) {
            dynamicTotalElement.innerHTML = `{!! \App\Helpers\Number::money('') !!}` + currentTotalPrice.toFixed(2);
        }
        if (multiplierElement) multiplierElement.textContent = timeSlotsMultiplier;
    }

    function updateSelectionSummary() {
        const summaryElement = document.getElementById('selection-summary');
        if (!summaryElement) return;

        let summaryHTML = '<h5>Your Selection:</h5>';
        
        if (selectedDuration) {
            summaryHTML += `
                <p><strong>Duration:</strong> ${selectedDuration.title}</p>
                <p><i class="fas fa-clock"></i> ${selectedDuration.duration}</p>
                <p><i class="fas fa-map-marker-alt"></i> ${selectedDuration.location}</p>
            `;
        }

        if (selectedTimeSlots.length > 0) {
            summaryHTML += `<p><strong>Selected Time Slots (${selectedTimeSlots.length}):</strong></p><ul>`;
            selectedTimeSlots.forEach(slot => {
                summaryHTML += `<li>${slot.day}: ${slot.openTime} - ${slot.closeTime}</li>`;
            });
            summaryHTML += '</ul>';
            summaryHTML += `<p><strong>Price Multiplier:</strong> ×${timeSlotsMultiplier}</p>`;
        }

        summaryElement.innerHTML = summaryHTML;
    }

    function validateTimeSlots() {
        const timeSlotsExist = "{{ $slots->isNotEmpty() }}";
        if (timeSlotsExist && selectedTimeSlots.length === 0) {
            const errorElement = document.getElementById('time-range-error');
            if (errorElement) {
                errorElement.style.display = 'block';
            }
            showNotification('error', '{{ t("Please select at least one time slot") }}');
            return false;
        }
        return true;
    }

    function handleCartAction(event) {
        event.preventDefault();
        
        if (!validateTimeSlots()) {
            return;
        }
        
        const isAuthenticated = "{{ auth()->check() }}";
        if (!isAuthenticated) {
            $('#quickLogin').modal('show');
            return;
        }

        updateTotalPrice();
        
        const postId = "{{ $post->id }}";
        const userId = "{{ auth()->id() }}";
        const productType = "{{ $post->type }}";
        const durationId = selectedDuration?.id || $('#selected-duration-id').val();

        const timeSlotsData = selectedTimeSlots.map(slot => ({
            day: slot.day,
            open_time: slot.openTime,
            close_time: slot.closeTime
        }));

        let selectedAddons = [];
        document.querySelectorAll('.addon-checkbox:checked').forEach(checkbox => {
            selectedAddons.push({
                id: checkbox.id.replace('addon-', ''),
                addonTitle: checkbox.dataset.title,
                price: parseFloat(checkbox.dataset.price)
            });
        });

        const data = {
            user_id: userId,
            post_id: postId,
            duration_id: durationId,
            time_slots: timeSlotsData,
            quantity: timeSlotsMultiplier, 
            base_price: basePrice,
            addons: selectedAddons,
            addons_total: currentAddonsTotal,
            total_price: currentTotalPrice,
            product_type: productType,
            time_slots_multiplier: timeSlotsMultiplier
        };

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: '{{ route('store.carts') }}',
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(data),
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function(response) {
                if (response.success) {
                    showNotification('success', '{{ t("item_added") }}');
                    if (response.cart_count) {
                        $('.cart-count').text(response.cart_count);
                    }
                } else {
                    showNotification('error', response.message || '{{ t("failed_add") }}');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || '{{ t("error_add") }}';
                console.error('Cart submission error:', errorMsg);
                showNotification('error', errorMsg);
            }
        });
    }

    function showNotification(type, message) {
        const notificationContainer = document.getElementById('notificationContainer');
        if (!notificationContainer) return;
        
        const notification = document.createElement('div');
        notification.classList.add('notification', type === 'error' ? 'error' : 'success');
        notification.innerHTML = `
            ${message}
            <button onclick="this.parentElement.remove()">×</button>
        `;
        notificationContainer.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }

    document.getElementById('add-to-cart')?.addEventListener('click', handleCartAction);
    document.getElementById('addtoCart')?.addEventListener('click', handleCartAction);

    document.querySelector(".toggle-btn")?.addEventListener("click", function() {
        let isToggled = this.getAttribute("data-toggle") === "true";
        let addonsSidebar = document.getElementById("addons-sidebar");
        let defaultSidebar = document.getElementById("default-sidebar");

        this.setAttribute("data-toggle", isToggled ? "false" : "true");
        document.getElementById("toggleState").value = isToggled ? "false" : "true";

        if (!isToggled) {
            this.classList.remove("btn-outline-danger");
            this.classList.add("btn-danger");
            defaultSidebar?.classList.add("d-none");
            addonsSidebar?.classList.remove("d-none");
        } else {
            this.classList.remove("btn-danger");
            this.classList.add("btn-outline-danger");
            addonsSidebar?.classList.add("d-none");
            defaultSidebar?.classList.remove("d-none");
        }
    });

    function bxSliderSettings() {
        return {
            minSlides: 2,
            maxSlides: 6,
            slideWidth: 70,
            slideMargin: 10,
            pager: false,
            nextText: '',
            prevText: '',
            moveSlides: 1,
            touchEnabled: true
        };
    }

    var mainSlider = $('.bxslider').bxSlider({
        touchEnabled: {{ ($post->pictures->count() > 1) ? 'true' : 'false' }},
        speed: 300,
        pagerCustom: '#bx-pager',
        adaptiveHeight: true,
        nextText: '{{ t('bxslider.nextText') }}',
        prevText: '{{ t('bxslider.prevText') }}',
        startText: '{{ t('bxslider.startText') }}',
        stopText: '{{ t('bxslider.stopText') }}',
        onSlideAfter: function ($slideElement, oldIndex, newIndex) {
            @if (!userBrowser('Chrome'))
                $('#bx-pager li:not(.bx-clone)').eq(newIndex).find('a.thumb-item-link').addClass('active');
            @endif
        }
    });

    @if (userBrowser('Chrome'))
        $('#bx-pager').addClass('m-3');
        $('#bx-pager .thumb-item-link').unwrap();
    @else
        var thumbSlider = $('.product-view-thumb').bxSlider(bxSliderSettings());
        $(window).on('resize', function () {
            thumbSlider.reloadSlider(bxSliderSettings());
        });
    @endif
});
</script>

@endsection