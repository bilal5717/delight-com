{{-- * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard --}}
@extends('layouts.master')

@section('after_styles')
    <style>
        .badge-primary {
            color: #fff !important;
        }

        .item-list {
            height: 450px !important;
        }

        .openinghourscontent h2 {
            display:block;
            text-align:center;
            margin-top:.33em;
        }
        .openinghourscontent button {
            color:white;
            font-size:large;
            font-weight:bolder;
            background-color:#4679BD;
            border-radius:4px;
            width:100%;
            margin-bottom:10px;
        }
        .opening-hours-table tr td:first-child {
            font-weight:bold;
        }
        .opening-hours-table tr td {
            padding:5px;
        }
        .availability-table {
            width: 100%;
            border-collapse: collapse;
        }
        .availability-table th, .availability-table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .availability-table th {
            background-color: #f2f2f2;
        }
    </style>
    <script type="application/ld+json">
        <?php
        $appURL = url('/');
        $pages = \App\Models\Page::orderBy('lft', 'ASC')->get();
        $countryCode = '';
        if (session()->has('country_code')) {
            $countryCode = session('country_code');
        }
        $legalStructure = '';
        $activeTab = $postData['activeTab'];
        $cityName = '';
        $countryName = '';
        $userAddress = '';
        if($company->companyAddresss->count() > 0) {
            $cityName = $company->defaultCompanyAddresss->city->name;
            $countryName = $company->defaultCompanyAddresss->city->country->name;
            $userAddress = $company->defaultCompanyAddresss->address;
        }

        foreach ($postTypes as $postType) {
            if ($company->default_business_type == $postType['id']) {
                $legalStructure = $postType['name'];
            }
        }

        $revenue = [
            '0 - 10,000 USD',
            '10,000 - 100,000 USD',
            '100,000 - 500,000 USD',
            '500,000 - 1000,000 USD',
            '1000,000USD - 10,000,000 USD',
            '10,000,000 USD'
        ];

        $workingHoursArray = [];
        if(isset($company->user->working_hours_active) && $company->user->working_hours_active == 'active')
        {
            $workingHours = json_decode($company->user->working_hours, true);
            $workingHoursArray = [];
            $days = [t('Monday'), t('Tuesday'), t('Wednesday'), t('Thursday'), t('Friday'), t('Saturday'), t('Sunday')];
            foreach ($workingHours as $key => $value) {
                $dayArray = [];
                $dayArray['day'] = $days[$key];
                $dayArray['openingTime'] = $value['timeFrom'];
                $dayArray['closingTime'] = $value['timeTill'];
                $dayArray['isActive'] = $value['isActive'];

                $workingHoursArray [] = $dayArray;

            }
        }

        $productsArray = [];
        $productCategoryArray = [];
        $relatedProductsArray = [];
        foreach ($postData['posts'] as $key => $post) {
            $productArray = [];
            $productArray['@type'] = ["Product", "Offer"];
            $productArray['@id'] = url('/product/' . $post->id);
            $productArray['name'] = $post->title;
            $productArray['description'] = $post->description;
            $productArray['price'] = $post->price;
            $productArray['image'] = imgUrl($post->pictures->get(0)->filename, 'medium');
            $productArray['category'] = \App\Helpers\UrlGen::category($post->category, null, $city ?? null);
            $productArray['rating'] = $post->rating_count;
            $postDateWithYear = $post->created_at->addYear()->format('d F Y');
            $productArray['offers'] = [
                '@type' => "Offer",
                'url' => url('/product/' . $post->id),
                'price' => $post->price ?? '',
                'priceCurrency' => $post->country->currency_code ?? '',
                'priceValidUntil' => $postDateWithYear,
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => 'https://schema.org/InStock'
            ];
            $productArray['hasMerchantReturnPolicy'] = [
                '@type' => "MerchantReturnPolicy",
                'name' => "30-day return policy",
                'url' => $appURL . '/page/terms',
            ];
            $productArray['shippingDetails'] = [
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
                        '@type' => "QuantitativeValue",
                        'minValue' => 7,
                        'maxValue' => 30,
                    ],
                ],
            ];
            $productArray['aggregateRating'] = [
                '@type' => "AggregateRating",
                'ratingValue' => 3.5,
                'reviewCount' => 5,
            ];
            $productArray['review'] = [
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
            ];

            $productsArray [] = $productArray;
            $productCategoryArray [] = $post->category;
            $categoryPosts = \App\Models\Post::where('category_id', $post->category->id)->get();
            foreach ($categoryPosts as $relatedPost) {
                $postDateWithYear = $relatedPost->created_at->addYear()->format('d F Y');
                $relatedProductsArray [] = [
                    '@type' => 'Product',
                    'name' => $relatedPost->title,
                    'url' => \App\Helpers\UrlGen::post($relatedPost),
                    'image' => imgUrl($post->pictures->get(0)->filename, 'medium'),
                    'offers' => [
                        '@type' => "Offer",
                        'url' => url('/product/' . $relatedPost->id),
                        'price' => $relatedPost->price ?? '',
                        'priceCurrency' => $relatedPost->country->currency_code ?? '',
                        'priceValidUntil' => $postDateWithYear,
                        'itemCondition' => 'https://schema.org/NewCondition',
                        'availability' => 'https://schema.org/InStock'
                    ],
                    'aggregateRating' => [
                        '@type' => "AggregateRating",
                        'ratingValue' => 3.5,
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
                    ]
                ];
            }

        }

        $productCategoryArrayResults = [];
        foreach ($productCategoryArray as $category) {

            $productCategoryArrayResults [] = [
                '@type' => 'ProductCategory',
                'name' => $category->name,
                'url' => \App\Helpers\UrlGen::category($category),
            ];
        }
        $key = 0;
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

        $structuredData = [
            '@context' => 'http://schema.org',
            '@type' => 'Organization',
            'name' => "Welcome to the Official Online Shop of " . $company->name . " in " . $cityName ?? '' . ", " . $countryName ?? '',
            'url' => url('/') . '/company/' . $company->company_slug, // Project URl for company page
            'description' => $company->description,
            'sameAs' => [
                isset($company->facebook) ? $company->facebook : null,
                isset($company->twitter) ? $company->twitter : null,
                isset($company->instagram) ? $company->instagram : null,
                isset($company->linkedin) ? $company->linkedin : null,
                isset($company->pinterest) ? $company->pinterest : null,
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $company->phone,
                'contactType' => 'Customer Service',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $userAddress->latitude ?? '',
                'longitude' => $userAddress->longitude ?? '',
            ],
            'numberOfEmployees' => '100-200',
            'foundingDate' => '2000',
            'registrationNumber' => $company->registration_number,
            'verificationStatus' => 'unverified',
            'revenue' => [
                '@type' => 'MonetaryAmount',
                'currency' => 'USD',
                'value' => $revenue[$company->revenue],
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Advertised Products',
                'itemListElement' => $productsArray,
            ],
            'openingHoursSpecification' => $workingHoursArray,
            'breadcrumb' => [
                '@type' => 'BreadcrumbList',
                'itemListElement' => $itemsListElementBreadcrumbs
            ],
            'relatedProducts' => [
                '@type' => 'ItemList',
                'name' => 'Similar Products',
                'itemListElement' => array_unique($relatedProductsArray, SORT_REGULAR)
            ],
            'relatedCategories' => [
                '@type' => 'ItemList',
                'name' => 'Similar Categories',
                'itemListElement' => $productCategoryArrayResults
            ]
        ];

        if(isset($structuredData))
        {
            // Convert to JSON
            $json_ld = json_encode($structuredData, JSON_PRETTY_PRINT);

            // Output the JSON-LD
            echo $json_ld;
        }

        $categoryArray = [];
        ?>
    </script>
@endsection

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">

        <div class="container {{ (isset($topAdvertising) and !empty($topAdvertising)) ? 'mt-3' : 'mt-2' }}">
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
                                {{ $company->name }}</li>
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
                <!--/.page-sidebar-->
                <div class="col-lg-3">
                    <div class="card card-user-info sidebar-card">
                        <div class="card-content user-info">
                            <div class="card-body text-center">
                                <div class="company-info">
                                    <div class="company-logo-thumb mb20 bg-light p-3">
                                        <a href="{{ \App\Helpers\UrlGen::user($company->user) }}">
                                            @if ($company->logo)
                                                <img class="rounded-circle border border-secondary p-1  w-50"
                                                     src="{{ asset('storage/' . $company->logo) }}" alt=""
                                                     height="80px">
                                            @else
                                                <img src="{{ asset('storage/app/default/picture.jpg') }}" alt="">
                                            @endif
                                        </a>
                                    </div>
                                    <strong>
                                        <a href="{{ \App\Helpers\UrlGen::user($company->user) }}" class="text-primary">
                                            <p class="text-primary" style="font-size: 18px;">
                                                {{ $company->name }}
                                            </p>
                                        </a>
                                    </strong>

                                    <p class="mb-1 mt-2">
                                        <strong>
                                            {{ t('address') }} :&nbsp;
                                        </strong>
                                        <strong>
                                            <a class="text-primary"
                                               href="{{ url('https://www.google.com/maps/place/' . $userAddress ?? '') }}">
                                                {{ $userAddress ?? '' }}
                                            </a>
                                        </strong>
                                    </p>

                                    <p class="mb-1">
                                        <strong>
                                            {{ t('company_city') }} :&nbsp;
                                        </strong>
                                        <strong>
                                            <span class="text-primary">
                                                {{ $company->defaultCompanyAddresss->city->name ?? '' }}
                                            </span>
                                        </strong>
                                    </p>
                                    <p class="mb-1">
                                        <strong>
                                            <i class="fas fa-globe"></i> &nbsp;{{ t('company_country') }}
                                            :&nbsp;
                                        </strong>
                                        <strong>
                                            <span class="text-primary">
                                                {{ $company->defaultCompanyAddresss->city->country->name ?? '' }}
                                            </span>
                                        </strong>
                                    </p>
                                    <p class="mb-1">
                                        <strong>
                                            <i class="fas fa-phone"></i> &nbsp;{{ t('phone') }} :
                                            &nbsp;
                                        </strong>

                                        <strong class="text-primary">
                                            {{ $company->phone }}
                                        </strong>
                                    </p>
                                    @if (
                                        $company->facebook ||
                                            $company->twitter ||
                                            $company->instagram ||
                                            $company->wechat ||
                                            $company->linkedin ||
                                            $company->kvk)
                                        <strong class="d-block mt-4">{{ t('business_social_media') }} </strong>

                                        <div class="row">
                                            @if ($company->facebook)
                                                <div class="col-4">
                                                    <a href="{{ $company->facebook }}">
                                                        <img src="{{ asset('images/facebook.png') }}" width="48"/>
                                                    </a>
                                                </div>
                                            @endif
                                            @if ($company->twitter)
                                                <div class="col-4">
                                                    <a href="{{ $company->twitter }}">
                                                        <img src="{{ asset('images/x.png') }}" width="48"/>
                                                    </a>
                                                </div>
                                            @endif
                                            @if ($company->instagram)
                                                <div class="col-4">
                                                    <a href="{{ $company->instagram }}">
                                                        <img src="{{ asset('images/insta.png') }}" width="48"/>
                                                    </a>
                                                </div>
                                            @endif
                                            @if ($company->wechat)
                                                <div class="col-4">
                                                    <a href="{{ $company->wechat }}">
                                                        <img src="{{ asset('images/wechat.png') }}" width="48"/>
                                                    </a>
                                                </div>
                                            @endif
                                            @if ($company->linkedin)
                                                <div class="col-4">
                                                    <a href="{{ $company->linkedin }}">
                                                        <img src="{{ asset('images/linkedin.png') }}" width="48"/>
                                                    </a>
                                                </div>
                                            @endif
                                            @if ($company->kvk)
                                                <div class="col-4">
                                                    <a href="{{ $company->kvk }}">
                                                        <img src="{{ asset('images/vk.png') }}" width="48"/>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                    @endif
                                    @if ($featured)
                                        <a class="btn btn-success"
                                           href="{{ \App\Helpers\UrlGen::user($company->user) }}"
                                           style="font-weight:bold">
                                            {{t("ads_by_company")}}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 page-content col-thin-right mb-4">
                    <div class="inner inner-box items-details-wrapper pb-0">
                        <div class="items-details">
                            <ul class="nav nav-tabs" id="itemsDetailsTabs" role="tablist">
                                <li class="nav-item nav-click" data-url="profile">
                                    <a class="nav-link @if($postData['activeTab'] == 'profile') active @endif"
                                       id="item-details-tab" data-toggle="tab"
                                       href="#item-details"
                                       role="tab" aria-controls="item-details" aria-selected="true">
                                        <h4>{{ t('about_company') }}</h4>
                                    </a>
                                </li>
                                <li class="nav-item nav-click" data-url="products">
                                    <a class="nav-link @if($postData['activeTab'] == 'products') active @endif"
                                       id="item-types-tab" data-toggle="tab" href="#item-types"
                                       role="tab" aria-controls="item-types" aria-selected="false">
                                        <h4>{{ t('company_products') }}</h4>
                                    </a>
                                </li>
                                <li class="nav-item nav-click" data-url="hours">
                                    <a class="nav-link @if($postData['activeTab'] == 'hours') active @endif"
                                       id="item-hours-tab" data-toggle="tab" href="#item-hours"
                                       role="tab" aria-controls="item-hours" aria-selected="false">
                                        <h4>{{ t('business_hours') }}</h4>
                                    </a>
                                </li>
                                <li class="nav-item nav-click" data-url="availability">
                                    <a class="nav-link @if($postData['activeTab'] == 'availability') active @endif"
                                       id="item-availability-tab" data-toggle="tab" href="#item-availability"
                                       role="tab" aria-controls="item-availability" aria-selected="false">
                                        <h4>{{ t('Schedules / Availability') }}</h4>
                                    </a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3 mb-3" id="itemsDetailsTabsContent">
                                <div class="tab-pane @if($postData['activeTab'] == 'profile') show active @endif"
                                     id="item-details" role="tabpanel"
                                     aria-labelledby="item-details-tab">
                                    <div class="border border-light rounded p-2 mb-3">
                                        <div class="d-flex">
                                            <a href="{{ \App\Helpers\UrlGen::user($company->user) }}">
                                                <img class="me-2 rounded" src="{{ asset('storage/' . $company->logo) }}"
                                                     alt="Generic placeholder image" height="35px">
                                            </a>
                                            <div>
                                                <a href="{{ \App\Helpers\UrlGen::user($company->user) }}"
                                                   class="text-primary">
                                                    <h5 class="m-0 p-2 ms-2">
                                                        {{ $company->name }}
                                                    </h5>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="p-3">
                                            <div class="row border-bottom mb-3">
                                                <strong class="text-primary">
                                                    <i class="fas fa-comment"></i>&nbsp;{{ t('about') }} :
                                                    <span class="text-muted">
                                                        {!! $company->description !!} &nbsp;
                                                    </span>
                                                </strong>
                                            </div>
                                            <div class="row border-bottom mb-3">
                                                <strong class="text-primary">
                                                    <i class="fas fa-phone"></i> &nbsp;{{ t('phone') }} :
                                                    &nbsp;
                                                </strong>

                                                <strong class="text-muted">
                                                    {{ $company->phone }}
                                                </strong>
                                            </div>
                                            <div class="row border-bottom mb-3">
                                                <strong class="text-primary">
                                                    <i
                                                            class="fas fa-chart-line"></i>&nbsp;{{ t('company_size') }}
                                                    : &nbsp;
                                                </strong>
                                                <strong class="text-muted">
                                                    {!! getRevenue($company->revenue) !!}
                                                </strong>
                                            </div>
                                            <div class="row border-bottom mb-3">
                                                <strong class="text-primary">
                                                    <i
                                                            class="fas fa-info"></i>&nbsp;{{ t('registration_number') }}
                                                    : &nbsp;
                                                </strong>
                                                <strong class="text-muted">
                                                    {{$company->registration_number}}
                                                </strong>
                                            </div>
                                            <div class="row border-bottom mb-3">
                                                <strong class="text-primary">
                                                    <i
                                                            class="fas fa-internet-explorer"></i>&nbsp;{{ t('website') }}
                                                    : &nbsp;
                                                </strong>
                                                <strong class="text-muted">
                                                    {{$company->website}}
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane @if($postData['activeTab'] == 'products') show active @endif"
                                     id="item-types" role="tabpanel"
                                     aria-labelledby="item-types-tab">
                                    <div class="row">
                                        <!-- Location -->
                                        <div class="type-line-lite col-md-12 col-sm-12 col-xs-12">
                                            <div>
                                                <div class="container hide-xs">
                                                    <div>
                                                        <ul class="list-inline">
                                                            <li class="list-inline-item mt-2">
                                                                <a class="badge badge-primary" data-cat="all">
                                                                    All
                                                                </a>
                                                            </li>
                                                            @foreach($postData['posts'] as $postForCat)
                                                                @if(!in_array($postForCat->category->name, $categoryArray))
                                                                    @php
                                                                        $categoryArray[] = $postForCat->category->name;
                                                                    @endphp
                                                                    <li class="list-inline-item mt-2">
                                                                        <a class="badge badge-light"
                                                                           data-cat="{{$postForCat->category->slug}}">
                                                                            {{$postForCat->category->name}}
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                            @endforeach

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="listing-filter">
                                                    <div class="pull-left col-xs-6">
                                                        <div class="breadcrumb-list">
                                                            {!! (isset($htmlTitle)) ? $htmlTitle : '' !!}
                                                        </div>
                                                        <div style="clear:both;"></div>
                                                    </div>

                                                    @if (isset($posts) and $posts->count() > 0)
                                                        <div class="pull-right col-xs-6 text-right listing-view-action">
                                                            <span class="list-view"><i class="icon-th"></i></span>
                                                            <span class="compact-view"><i
                                                                        class="icon-th-list"></i></span>
                                                            <span class="grid-view active"><i class="icon-th-large"></i></span>
                                                        </div>
                                                    @endif

                                                    <div style="clear:both"></div>
                                                </div>
                                                <div class="category-list make-grid">
                                                    <div id="postsList" class="adds-wrapper row no-margin">
                                                        <?php
                                                        foreach ($postData['posts'] as $key => $post):
                                                            // Main Picture
                                                            if ($post->pictures->count() > 0) {
                                                                $postImg = imgUrl($post->pictures->get(0)->filename, 'medium');
                                                            } else {
                                                                $postImg = imgUrl(config('larapen.core.picture.default'), 'medium');
                                                            }
                                                            ?>
                                                        <div class="item-list all-items {{$post->category->slug}}"
                                                             style="height: 450px!important;">
                                                            @if ($post->featured == 1)
                                                                @if (isset($post->latestPayment, $post->latestPayment->package) && !empty($post->latestPayment->package))
                                                                    @if ($post->latestPayment->package->ribbon != '')
                                                                        <div class="cornerRibbons {{ $post->latestPayment->package->ribbon }}">
                                                                            <a href="#"> {{ $post->latestPayment->package->short_name }}</a>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            @endif

                                                            <div class="row">
                                                                <div class="col-sm-2 col-12 no-padding photobox">
                                                                    <div class="add-image">

                                                                        <a href="{{ \App\Helpers\UrlGen::post($post) }}">
                                                                            <img class="lazyload img-thumbnail no-margin"
                                                                                 src="{{ $postImg }}"
                                                                                 alt="{{ $post->title }}">
                                                                        </a>
                                                                    </div>
                                                                </div>

                                                                <div class="col-sm-7 col-12 add-desc-box">
                                                                    <div class="items-details">
                                                                        <h5 class="add-title">
                                                                            <a href="{{ \App\Helpers\UrlGen::post($post) }}">{{ \Illuminate\Support\Str::limit($post->title, 40, "...") }} </a>
                                                                        </h5>

                                                                        <span class="info-row">
						@if (config('settings.single.show_post_types'))
                                                                                @if (isset($post->postType) && !empty($post->postType))
                                                                                    <span class="add-type business-ads tooltipHere"
                                                                                          data-toggle="tooltip"
                                                                                          data-placement="bottom"
                                                                                          title="{{ $post->postType->name }}"
                                                                                    >
									{{ strtoupper(mb_substr($post->postType->name, 0, 1)) }}
								</span>&nbsp;
                                                                                @endif
                                                                            @endif
                                                                            @if (!config('settings.listing.hide_dates'))
                                                                                <span class="date"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
								<i class="icon-clock"></i> {!! $post->created_at_formatted !!}
							</span>
                                                                            @endif
						<span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
							<i class="icon-folder-circled"></i>&nbsp;
							@if (isset($post->category->parent) && !empty($post->category->parent))
                                <a href="{!! \App\Helpers\UrlGen::category($post->category->parent, null, $city ?? null) !!}"
                                   class="info-link">
									{{ $post->category->parent->name }}
								</a>&nbsp;&raquo;&nbsp;
                            @endif
							<a href="{!! \App\Helpers\UrlGen::category($post->category, null, $city ?? null) !!}"
                               class="info-link">
								{{ $post->category->name }}
							</a>
						</span>
					</span>
                                                                    </div>
                                                                    @if (config('plugins.reviews.installed'))

                                                                        @if (view()->exists('reviews::ratings-list'))
                                                                            @include('reviews::ratings-list')
                                                                        @endif
                                                                    @endif
                                                                </div>

                                                                <div class="col-sm-3 col-12 text-right price-box"
                                                                     style="white-space: nowrap;">
                                                                    <h4 class="item-price">
                                                                        @if (isset($post->category->type))
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
                                                                    </h4>&nbsp;
                                                                    @if (isset($post->latestPayment, $post->latestPayment->package) && !empty($post->latestPayment->package))
                                                                        @if ($post->latestPayment->package->has_badge == 1)
                                                                            <a class="btn btn-danger btn-sm make-favorite">
                                                                                <i class="fa fa-certificate"></i>
                                                                                <span> {{ $post->latestPayment->package->short_name }} </span>
                                                                            </a>&nbsp;
                                                                        @endif
                                                                    @endif
                                                                    @if (isset($post->savedByLoggedUser) && $post->savedByLoggedUser->count() > 0)
                                                                        <a class="btn btn-success btn-sm make-favorite"
                                                                           id="{{ $post->id }}">
                                                                            <i class="fa fa-heart"></i><span> {{ t('Saved') }} </span>
                                                                        </a>
                                                                    @else
                                                                        <a class="btn btn-default btn-sm make-favorite"
                                                                           id="{{ $post->id }}">
                                                                            <i class="fa fa-heart"></i><span> {{ t('Save') }} </span>
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane @if($postData['activeTab'] == 'hours') show active @endif"
                                     id="item-hours" role="tabpanel"
                                     aria-labelledby="item-hours-tab">
                                    <div class="row">
                                        <!-- Location -->
                                        <div class="type-line-lite col-md-12 col-sm-12 col-xs-12">
                                            <div>
                                                @php
                                                    $activeDaysArray = [];
                                                    $days = [t('Monday'), t('Tuesday'), t('Wednesday'), t('Thursday'), t('Friday'), t('Saturday'), t('Sunday')];
                                                    if(isset($company->user->working_hours_active) && $company->user->working_hours_active == 'active'){
                                                        $workingHours = json_decode($company->user->working_hours, true);
                                                        echo '<table class="opening-hours-table">';
                                                        foreach ($workingHours as $key=>$value)
                                                        {
                                                            if($value['isActive'])
                                                            {
                                                                echo "<tr>";
                                                                echo "<td>".$days[$key]."</td>";
                                                                echo "<td class='opens'>". $value['timeFrom']. "</td>";
                                                                echo "<td>-</td>";
                                                                echo "<td class='closes'>" .$value['timeTill']. "</td>";
                                                                echo "</tr>";
                                                            }
                                                        }
                                                        echo '</table>';
                                                    }
                                                    else
                                                    {
                                                        echo "<h4>".t('working_hour_active_text')."</h4>";
                                                    }
                                                @endphp
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="tab-pane @if($postData['activeTab'] == 'availability') show active @endif"
     id="item-availability" role="tabpanel"
     aria-labelledby="item-availability-tab">
    <div class="row">
        <div class="col-12">
            <div class="weekly-schedule-container">
                <div class="d-flex justify-content-between align-items-end mb-3 date-picker-container">
                    <div class="date-label-wrapper">
                        <label class="form-label fw-bold" style="font-size: 1.15rem;">{{ t('Select date to see coming 7 days') }}:</label>
                    </div>
                    <div class="date-picker-wrapper" style="width: 400px;">
                        <div class="input-group">
                            <button type="button" class="btn btn-outline-secondary date-nav-btn" id="prev-date-btn" title="Previous day">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <input id="date-picker-input" class="form-control" placeholder="mm/dd/yyyy" value="{{ now()->format('m/d/Y') }}"/>
                            <button type="button" class="btn btn-outline-secondary date-nav-btn" id="next-date-btn" title="Next day">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            
                        </div>
                        <div id="date-picker-error" class="text-danger mt-1 d-none">
                            Failed to load date picker. Please try refreshing the page.
                        </div>
                    </div>
                </div>

                @if($postData['count'] > 0)
                    <!-- Initial schedule will be rendered here by PHP -->
                    <div id="schedule-table-container">
                        <div class="card mb-4 shadow-sm">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <tbody>
                                        @php
                                            $currentDate = now();
                                            $weekDays = [];
                                            for ($i = 0; $i < 7; $i++) {
                                                $date = $currentDate->copy()->addDays($i);
                                                $weekDays[] = [
                                                    'date' => $date,
                                                    'dayName' => $date->format('l'),
                                                    'dayShort' => $date->format('D'),
                                                    'month' => $date->format('M'),
                                                    'day' => $date->format('j'),
                                                    'year' => $date->format('Y'),
                                                    'fullDate' => $date->format('Y-m-d')
                                                ];
                                            }
                                            
                                            // Collect all classes for each day from all posts
                                            $allClassesByDay = [];
                                            foreach ($weekDays as $weekDay) {
                                                $dayName = $weekDay['dayName'];
                                                $allClassesByDay[$dayName] = [];
                                                
                                                foreach ($postData['posts'] as $post) {
                                                    $timeRange = json_decode($post->time_range, true);
                                                    
                                                    if (isset($timeRange['slots'])) {
                                                        foreach ($timeRange['slots'] as $slot) {
                                                            if ($slot['day'] == $dayName && !($slot['disabled'] ?? false)) {
                                                                foreach ($slot['time_ranges'] as $timeRange) {
                                                                    if ($timeRange['enabled'] ?? false) {
                                                                        $durationValue = '';
                                                                        $durationTitle = '';
                                                                        $durationId = null;
                                                                        $maxCapacity = 0;
                                                                        $availableUnits = 0;
                                                                        $isAvailable = false;
                                                                        foreach ($post->durations as $duration) {
                                                                            if ($duration->duration_title == $timeRange['title'] && 
                                                                                $duration->open_time == $timeRange['open_time'] . ':00') {
                                                                                $durationValue = $duration->duration_value;
                                                                                $durationId = $duration->id;
                                                                                $durationTitle = $duration->duration_title;
                                                                                $maxCapacity = $duration->max_capacity;
                                                                                $availableUnits = $duration->available_units;
                                                                                $isAvailable = $availableUnits > 0 && $duration->is_active;
                                                                                break;
                                                                            }
                                                                        }
                                                                        $allClassesByDay[$dayName][] = [
                                                                            'post_id' => $post->id,
                                                                            'post_title' => $post->title,
                                                                            'post_url' => \App\Helpers\UrlGen::post($post),
                                                                            'post_price' => $post->price,
                                                                            'open_time' => $timeRange['open_time'],
                                                                            'close_time' => $timeRange['close_time'],
                                                                            'duration' => $durationValue ? $durationValue . ' ' . t('min') : '60 ' . t('min'),
                                                                            'duration_id' => $durationId,
                                                                            'duration_title' => $durationTitle,
                                                                            'max_capacity' => $maxCapacity,
                                                                            'available_units' => $availableUnits,
                                                                            'is_available' => $isAvailable 
                                                                        ];
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        
                                        @foreach($weekDays as $weekDay)
                                            @php
                                                $dayName = $weekDay['dayName'];
                                                $dayClasses = $allClassesByDay[$dayName] ?? [];
                                            @endphp
                                            
                                            @if(count($dayClasses) > 0)
                                                <tr class="bg-primary text-white">
                                                    <th class="align-middle" style="width: 200px;" colspan='2'>
                                                        <div>
                                                            <strong>{{ t($weekDay['dayName']) }}</strong>
                                                            <div class="small">{{ $weekDay['month'] }} {{ $weekDay['day'] }}, {{ $weekDay['year'] }}</div>
                                                        </div>
                                                    </th>
                                                    <th class="align-middle">{{ t('Duration') }}</th>
                                                    <th class="align-middle">{{ t('Class Title') }}</th>
                                                    <th class="align-middle">{{ t('available') }}</th>
                                                    <th></th>
                                                </tr>
                                                
                                                @foreach($dayClasses as $class)
                                                    <tr class="border-bottom">
                                                        <td class="align-middle font-weight-bold" style="width: 200px;" colspan='2'>
                                                            {{ $class['open_time'] }} - {{ $class['close_time'] }} ({{ $class['duration'] }})
                                                        </td>
                                                        <td class="align-middle">{{ $class['duration_title'] }}</td>
                                                        <td class="align-middle">
                                                            <a href="{{ $class['post_url'] }}" class="text-primary font-weight-bold">
                                                                {{ $class['post_title'] }}
                                                            </a>
                                                            <span class="post-price d-none">{{ $class['post_price'] }}</span>
                                                            @if($class['duration_id'])
                                                                <span class="duration-id d-none" data-duration-id="{{ $class['duration_id'] }}"></span>
                                                            @endif
                                                        </td>
                                                        <td class="align-middle">
                                                            <span class="badge badge-light border">
                                                                {{ $class['available_units'] }}/{{ $class['max_capacity'] }}
                                                            </span>
                                                        </td>
                                                        <td class="align-middle">
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-success book-now-btn @if(!$class['is_available']) d-none @endif"
                                                                    data-post-id="{{ $class['post_id'] }}"
                                                                    data-day="{{ $weekDay['fullDate'] }}"
                                                                    data-time="{{ $class['open_time'] }}-{{ $class['close_time'] }}"
                                                                    data-service="{{ $class['post_title'] }}"
                                                                    @if($class['duration_id']) 
                                                                        data-duration-id="{{ $class['duration_id'] }}"
                                                                    @endif
                                                                    @if(!$class['is_available']) disabled @endif>
                                                                {{ t('BOOK NOW') }}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden div to store all schedule data for JavaScript -->
                    <div id="all-schedule-data" 
                         data-schedule="{{ json_encode([
                             'posts' => $postData['posts'],
                             'allClassesByDay' => $allClassesByDay
                         ]) }}" 
                         style="display: none;">
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        {{ t('No posts available with schedule information') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include Flatpickr CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Custom CSS to style Flatpickr and the icon -->
<style>
    .date-picker-wrapper .form-label {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 600;
        color: #161616;
        margin-bottom: 0.5rem;
    }
    .date-picker-wrapper .form-control {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 0.875rem;
        padding: 0.5rem 2.5rem 0.5rem 1rem; /* Extra padding-right for icon */
        border: 1px solid #8d8d8d;
        border-radius: 4px;
        background-color: #f4f4f4;
        color: #161616;
    }
    .date-picker-wrapper .form-control:focus {
        outline: 2px solid #0f62fe;
        border-color: #0f62fe;
        background-color: #ffffff;
    }
    .date-picker-wrapper .input-group-text {
        background-color: #f4f4f4;
        border: 1px solid #8d8d8d;
        border-left: none;
        border-radius: 0 4px 4px 0;
        padding: 0.5rem;
    }
    .flatpickr-calendar {
        font-family: 'IBM Plex Sans', sans-serif;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
    .flatpickr-day.selected, .flatpickr-day.selected:hover {
        background: #0f62fe;
        border-color: #0f62fe;
        color: #ffffff;
    }
    
    /* Date navigation buttons styling */
    .date-nav-btn {
        border: 1px solid #8d8d8d;
        background-color: #f4f4f4;
        color: #161616;
        padding: 0.5rem 0.75rem;
        transition: all 0.2s ease;
    }
    
    .date-nav-btn:hover {
        background-color: #e6e6e6;
        border-color: #6c757d;
        color: #161616;
    }
    
    .date-nav-btn:focus {
        box-shadow: 0 0 0 0.2rem rgba(15, 98, 254, 0.25);
        border-color: #0f62fe;
    }
    
    .date-nav-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Adjust input group styling for the new buttons */
    .date-picker-wrapper .input-group .form-control {
        border-left: none;
        border-right: none;
        border-radius: 0;
    }
    
    .date-picker-wrapper .input-group .btn:first-child {
        border-radius: 4px 0 0 4px;
    }
    
    .date-picker-wrapper .input-group .btn:nth-child(3) {
        border-radius: 0 4px 4px 0;
    }
    
    /* Mobile responsive styles */
    @media (max-width: 768px) {
        .date-picker-container {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        .date-label-wrapper {
            width: 100% !important;
            margin-bottom: 10px;
        }
        
        .date-picker-wrapper {
            width: 100% !important;
        }
        
        .date-label-wrapper .form-label {
            text-align: center;
            margin-bottom: 8px;
        }
    }
</style>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @includeFirst(
                           [config('larapen.core.customizedViewPath') . 'account.inc.vendor', 'account.inc.vendor'],
                           ['firstSection' => false]
                           )
            @includeFirst(
                [config('larapen.core.customizedViewPath') . 'account.inc.random', 'account.inc.random'],
                ['firstSection' => false]
            )
        </div>
    </div>
@endsection

@section('after_styles')
    <style>
        .vk {
            background: #2787F5;
        }

        .linkedin {
            background: #0077B5;
        }

        .wechat {
            background: #2DC100;
        }
        .time-slot-option {
        transition: all 0.2s ease;
        background-color: white;
    }
    .time-slot-option:hover {
        background-color: #f8f9fa;
    }
    .time-slot-checkbox:checked + .time-slot-option {
        background-color: #007bff;
        color: white;
        border-color: #006fe6;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    </style>
@endsection

@section('after_scripts')
    <script>
        $(".badge").on('click', function (event) {
            let selectedCat = this.dataset.cat;

            if (selectedCat == 'all') {
                $('.all-items').show();
            } else {
                $('.all-items').hide();
                $('.' + selectedCat).show();
            }

            $('.badge-primary').removeClass("badge-primary");
            $(this).addClass("badge-primary");
        });


        $('.nav-click').click(function () {
            var toUrl = $(this).data('url');

            var url = window.location.toString();
            var parts = url.split('/');
            var lastSegment = parts.pop() || parts.pop();

            window.location.replace(url.replace(lastSegment, toUrl));
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(document).ready(function() {
    // Initialize all schedule data from PHP
    const allScheduleData = {!! json_encode([
        'posts' => $postData['posts'],
        'weekDays' => $weekDays ?? [],
        'allClassesByDay' => $allClassesByDay ?? []
    ]) !!};

    // Function to render schedule for a specific start date
    function renderSchedule(startDate) {
        const weekDays = [];
        for (let i = 0; i < 7; i++) {
            const date = new Date(startDate);
            date.setDate(date.getDate() + i);
            
            weekDays.push({
                date: date,
                dayName: date.toLocaleDateString('en-US', { weekday: 'long' }),
                dayShort: date.toLocaleDateString('en-US', { weekday: 'short' }),
                month: date.toLocaleDateString('en-US', { month: 'short' }),
                day: date.getDate(),
                year: date.getFullYear(),
                fullDate: date.toISOString().split('T')[0]
            });
        }

        // Filter and organize classes for these days
        const allClassesByDay = {};
        weekDays.forEach(weekDay => {
            allClassesByDay[weekDay.dayName] = allScheduleData.allClassesByDay[weekDay.dayName] || [];
        });

        // Generate HTML for the schedule
        let html = `<div class="card mb-4 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <tbody>`;

        weekDays.forEach(weekDay => {
            const dayClasses = allClassesByDay[weekDay.dayName] || [];
            
            if (dayClasses.length > 0) {
                html += `<tr class="bg-primary text-white">
                    <th class="align-middle" style="width: 200px;" colspan='2'>
                        <div>
                            <strong>${weekDay.dayName}</strong>
                            <div class="small">${weekDay.month} ${weekDay.day}, ${weekDay.year}</div>
                        </div>
                    </th>
                    <th class="align-middle">Duration</th>
                    <th class="align-middle">Class Title</th>
                    <th class="align-middle">Available</th>
                    <th></th>
                </tr>`;
                
                dayClasses.forEach(classItem => {
                    html += `<tr class="border-bottom">
                        <td class="align-middle font-weight-bold" style="width: 200px;" colspan='2'>
                            ${classItem.open_time} - ${classItem.close_time} (${classItem.duration})
                        </td>
                        <td class="align-middle">${classItem.duration_title}</td>
                        <td class="align-middle">
                            <a href="${classItem.post_url}" class="text-primary font-weight-bold">
                                ${classItem.post_title}
                            </a>
                            <span class="post-price d-none">${classItem.post_price}</span>
                            ${classItem.duration_id ? `<span class="duration-id d-none" data-duration-id="${classItem.duration_id}"></span>` : ''}
                        </td>
                        <td class="align-middle">
                            <span class="badge badge-light border">
                                ${classItem.available_units}/${classItem.max_capacity}
                            </span>
                        </td>
                        <td class="align-middle">
                            <button type="button" 
                                    class="btn btn-sm btn-success book-now-btn ${!classItem.is_available ? 'd-none' : ''}"
                                    data-post-id="${classItem.post_id}"
                                    data-day="${weekDay.fullDate}"
                                    data-time="${classItem.open_time}-${classItem.close_time}"
                                    data-service="${classItem.post_title}"
                                    ${classItem.duration_id ? `data-duration-id="${classItem.duration_id}"` : ''}
                                    ${!classItem.is_available ? 'disabled' : ''}>
                                BOOK NOW
                            </button>
                        </td>
                    </tr>`;
                });
            }
        });

        html += `</tbody></table></div></div>`;
        
        $('#schedule-table-container').html(html);
        initializeBookingButtons();
    }

    // Initialize booking buttons
    function initializeBookingButtons() {
        $('.book-now-btn').off('click').on('click', function(e) {
            e.preventDefault();
            const button = $(this);
            const postElement = button.closest('tr'); 
            const originalText = button.text();
            button.prop('disabled', true)
                 .html('<i class="fas fa-spinner fa-spin"></i> ' + originalText);
            
            const postId = button.data('post-id');
            const day = button.data('day');
            const timeRange = button.data('time');
            const service = button.data('service');
            const [startTime, endTime] = timeRange.split('-');
            const postPrice = parseFloat(postElement.find('.post-price').text().trim()) || 0;
          
            let durationId = button.data('duration-id') || null;
            if (durationId) {
                durationId = parseInt(durationId);
                if (isNaN(durationId)) {
                    durationId = null;
                }
            }
            
            const bookingData = {
                user_id: {{ auth()->id() ?? 'null' }},
                post_id: postId,
                time_slots: [{
                    day: day,
                    open_time: startTime,
                    close_time: endTime
                }],
                quantity: 1,
                base_price: postPrice,
                addons: [],
                addons_total: 0.00,
                total_price: postPrice,
                product_type: 'class',
                time_slots_multiplier: 1
            };
            
            if (durationId) {
                bookingData.duration_id = durationId;
            }
            
            if (!bookingData.user_id) {
                showNotification('Please login to book this service', 'error');
                button.prop('disabled', false).html(originalText);
                window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href);
                return;
            }
            
            $.ajax({
                url: '{{ route("store.carts") }}',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(bookingData),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showNotification(response.message || 'Booking added to cart!', 'success');
                        if (response.cartCount) {
                            $('.cart-count').text(response.cartCount);
                        }
                    } else {
                        showNotification(response.message || 'Booking failed. Please try again.', 'error');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.errors) {
                            errorMsg = Object.entries(xhr.responseJSON.errors)
                                .map(([field, errors]) => `${field}: ${errors.join(', ')}`)
                                .join('<br>');
                        } else if (xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }
                    }
                    showNotification(errorMsg, 'error');
                },
                complete: function() {
                    button.prop('disabled', false).html(originalText);
                }
            });
        });
    }

    // Helper functions
    function showNotification(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
        
        const notification = $(`
            <div class="alert ${alertClass} alert-dismissible fade show notification-alert" 
                 role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                <i class="fas ${icon} me-2"></i>
                ${message}
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.alert('close');
        }, 5000);
    }

    // Initialize date picker
    let datePicker;
    try {
        datePicker = flatpickr('#date-picker-input', {
            dateFormat: 'm/d/Y',
            defaultDate: new Date(),
            minDate: 'today',
            onChange: function(selectedDates) {
                if (selectedDates.length > 0) {
                    renderSchedule(selectedDates[0]);
                    updateNavigationButtons(selectedDates[0]);
                }
            }
        });

        // Hide error message if Flatpickr initializes
        document.getElementById('date-picker-error').classList.add('d-none');
        
        // Render initial schedule with today's date
        renderSchedule(new Date());
        updateNavigationButtons(new Date());
        
        // Add navigation button event listeners
        $('#prev-date-btn').on('click', function() {
            const currentDate = datePicker.selectedDates[0] || new Date();
            const prevDate = new Date(currentDate);
            prevDate.setDate(prevDate.getDate() - 1);
            
            // Check if the previous date is not in the past
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            if (prevDate >= today) {
                datePicker.setDate(prevDate);
                // Explicitly refresh the schedule
                renderSchedule(prevDate);
                updateNavigationButtons(prevDate);
            }
        });
        
        $('#next-date-btn').on('click', function() {
            const currentDate = datePicker.selectedDates[0] || new Date();
            const nextDate = new Date(currentDate);
            nextDate.setDate(nextDate.getDate() + 1);
            datePicker.setDate(nextDate);
            // Explicitly refresh the schedule
            renderSchedule(nextDate);
            updateNavigationButtons(nextDate);
        });
        
    } catch (error) {
        console.error('Failed to initialize date picker:', error);
        document.getElementById('date-picker-error').classList.remove('d-none');
    }
    
    // Function to update navigation button states
    function updateNavigationButtons(selectedDate) {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        const prevBtn = $('#prev-date-btn');
        const nextBtn = $('#next-date-btn');
        
        // Disable previous button if selected date is today
        if (selectedDate <= today) {
            prevBtn.prop('disabled', true);
        } else {
            prevBtn.prop('disabled', false);
        }
        
        // Next button is always enabled (no upper limit)
        nextBtn.prop('disabled', false);
    }
});
</script>
@endsection