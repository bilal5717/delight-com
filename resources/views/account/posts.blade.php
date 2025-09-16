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

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container">
            <div class="row">

                @if (Session::has('flash_notification'))
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-xl-12">
                                @include('flash::message')
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="col-md-3 page-sidebar">
                    @includeFirst([
                        config('larapen.core.customizedViewPath') . 'account.inc.sidebar',
                        'account.inc.sidebar',
                    ])
                </div>
                <!--/.page-sidebar-->

                <div class="col-md-9 page-content">
                    <div class="inner-box">
                        @if ($pagePath == 'my-posts')
                            <h2 class="title-2"><i class="icon-docs"></i> {{ t('my_ads') }} </h2>
                        @elseif ($pagePath == 'archived')
                            <h2 class="title-2"><i class="icon-folder-close"></i> {{ t('archived_ads') }} </h2>
                        @elseif ($pagePath == 'favourite')
                            <h2 class="title-2"><i class="icon-heart-1"></i> {{ t('favourite_ads') }} </h2>
                        @elseif ($pagePath == 'pending-approval')
                            <h2 class="title-2"><i class="icon-hourglass"></i> {{ t('pending_approval') }} </h2>
                        @else
                            <h2 class="title-2"><i class="icon-docs"></i> {{ t('posts') }} </h2>
                        @endif

                        <div class="table-responsive">
                            <form name="listForm" method="POST" action="{{ url('account/' . $pagePath . '/delete') }}">
                                {!! csrf_field() !!}
                                <div class="table-action">
                                    <label for="checkAll">
                                        <input type="checkbox" id="checkAll">
                                        {{ t('Select') }}: {{ t('All') }} |
                                        <button type="submit" class="btn btn-sm btn-default delete-action">
                                            <i class="fa fa-trash"></i> {{ t('Delete') }}
                                        </button>
                                        <button type="submit" class="upgrade btn btn-sm btn-default">
                                            <i class="fas fa-arrow-alt-circle-up"></i> {{ t('upgrade_post_package') }}
                                        </button>
                                    </label>
                                    <div class="table-search pull-right col-sm-7">
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="col-sm-5 control-label text-right">{{ t('search') }} <br>
                                                    <a title="clear filter" class="clear-filter"
                                                        href="#clear">[{{ t('clear') }}]</a>
                                                </label>
                                                <div class="col-sm-7 searchpan">
                                                    <input type="text" class="form-control" id="filter">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table id="addManageTable"
                                    class="table table-striped table-bordered add-manage-table table demo"
                                    data-filter="#filter" data-filter-text-only="true">
                                    <thead>
                                        <tr>
                                            <th data-type="numeric" data-sort-initial="true"></th>
                                            <th>{{ t('Photo') }}</th>
                                            <th data-sort-ignore="true">{{ t('Ads Details') }}</th>
                                            <th data-type="numeric">--</th>
                                            <th>{{ t('Option') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
									if (isset($posts) && $posts->count() > 0):
									foreach($posts as $key => $post):
										// Fixed 1
										if ($pagePath == 'favourite') {
											if (isset($post->post)) {
												if (!empty($post->post)) {
													$post = $post->post;
												} else {
													continue;
												}
											} else {
												continue;
											}
										}

										// Fixed 2
										if (!$countries->has($post->country_code)) continue;

										// Get Post's URL
										$postUrl = \App\Helpers\UrlGen::post($post);
                                    	// Get Post's Pictures
                                        if ($post->pictures->count() > 0) {
                                            $postImg = imgUrl($post->pictures->get(0)->filename, 'medium');
                                        } else {
                                            $postImg = imgUrl(config('larapen.core.picture.default'), 'medium');
                                        }

                                    	// Get country flag
                                    	$countryFlagPath = 'images/flags/16/' . strtolower($post->country_code) . '.png';
									?>
                                        <tr>
                                            <td style="width:2%" class="add-img-selector">
                                                <div class="checkbox">
                                                    <label><input type="checkbox" name="entries[]"
                                                            value="{{ $post->id }}"></label>
                                                </div>
                                            </td>
                                            <td style="width:14%" class="add-img-td">
                                                <a href="{{ $postUrl }}"><img class="img-thumbnail img-fluid"
                                                        src="{{ $postImg }}" alt="img"></a>
                                            </td>
                                            <td style="width:58%" class="items-details-td">
                                                <div>
                                                    <p>
                                                        <strong>
                                                            <a href="{{ $postUrl }}"
                                                                title="{{ $post->title }}">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</a>
                                                        </strong>
                                                        @if (in_array($pagePath, ['my-posts', 'archived', 'pending-approval']))
                                                            @if (isset($post->latestPayment) and !empty($post->latestPayment))
                                                                @if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
                                                                    <?php
                                                                    if ($post->featured == 1) {
                                                                        $color = $post->latestPayment->package->ribbon;
                                                                        $packageInfo = '';
                                                                    } else {
                                                                        $color = '#ddd';
                                                                        $packageInfo = ' (' . t('Expired') . ')';
                                                                    }
                                                                    ?>
                                                                    <i class="fa fa-check-circle tooltipHere"
                                                                        style="color: {{ $color }};" title=""
                                                                        data-placement="bottom" data-toggle="tooltip"
                                                                        data-original-title="{{ $post->latestPayment->package->short_name . $packageInfo }}"></i>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    </p>
                                                    <p>
                                                        <strong>
                                                            <i class="icon-clock" title="{{ t('Posted On') }}"></i>
                                                        </strong>&nbsp;{!! $post->created_at_formatted !!}
                                                    </p>
                                                    <p>
                                                        <strong><i class="icon-eye"
                                                                title="{{ t('Visitors') }}"></i></strong>
                                                        {{ $post->visits ?? 0 }}
                                                        <strong><i class="icon-location-2"
                                                                title="{{ t('Located In') }}"></i></strong>
                                                        {{ !empty($post->city) ? $post->city->name : '-' }}
                                                        @if (file_exists(public_path($countryFlagPath)))
                                                            <img src="{{ url($countryFlagPath) }}" data-toggle="tooltip"
                                                                title="{{ $post->country->name }}">
                                                        @endif
                                                    </p>
                                                </div>
                                                
                                            </td>
                                            <td style="width:16%" class="price-td">
                                                <div>
                                                    <strong>
                                                        @if (is_numeric($post->price) && $post->price > 0)
                                                            {!! \App\Helpers\Number::money($post->price) !!}
                                                        @elseif (is_numeric($post->price) && $post->price == 0)
                                                            {!! t('free_as_price') !!}
                                                        @else
                                                            {!! \App\Helpers\Number::money(' --') !!}
                                                        @endif
                                                    </strong>
                                                </div>
                                            </td>
                                            <td style="width:10%" class="action-td">
                                                <div>
                                                    @if (in_array($pagePath, ['my-posts', 'pending-approval']) and
                                                        $post->user_id == $user->id and
                                                        $post->archived == 0)
                                                        <p>
                                                            <a class="btn btn-primary btn-sm"
                                                                href="{{ \App\Helpers\UrlGen::editPost($post) }}">
                                                                <i class="fa fa-edit"></i> {{ t('Edit') }}
                                                            </a>
                                                        </p>
                                                    @endif
                                                    @if (in_array($pagePath, ['my-posts']) and isVerifiedPost($post) and $post->archived == 0)
                                                        <p>
                                                            <a class="btn btn-warning btn-sm confirm-action"
                                                                href="{{ url('account/' . $pagePath . '/' . $post->id . '/offline') }}">
                                                                <i class="icon-eye-off"></i> {{ t('Offline') }}
                                                            </a>
                                                        </p>
                                                    @endif
                                                    @if($post->product_type_id == 1)
                                                    <p>
                                                            <a class="btn btn-success btn-sm"
                                                            href="{{ url('account/' . $pagePath . '/' . $post->id . '/addons') }}">
                                                                <i class="fas fa-puzzle-piece"></i>{{ t('addons') }}
                                                            </a>
                                                        </p>
                                                        @endif
                                                    @if (in_array($pagePath, ['archived']) and $post->user_id == $user->id and $post->archived == 1)
                                                        <p>
                                                            <a class="btn btn-info btn-sm confirm-action"
                                                                href="{{ url('account/' . $pagePath . '/' . $post->id . '/repost') }}">
                                                                <i class="fa fa-recycle"></i> {{ t('Repost') }}
                                                            </a>
                                                        </p>
                                                    @endif
                                                    <p>
                                                        <a class="btn btn-danger btn-sm delete-action"
                                                            href="{{ url('account/' . $pagePath . '/' . $post->id . '/delete') }}">
                                                            <i class="fa fa-trash"></i> {{ t('Delete') }}
                                                        </a>
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                        @if($post->product_type_id == 1)
                                            <tr style="padding: 30px 10px; height: auto;">
                                                <td colspan="5">
                                                    <div class="booking-types d-flex flex-wrap align-items-center justify-content-start">
                                                        <div class="row w-100">
                                                            <div class="col-12">
                                                                <div class="col-12 d-flex flex-row flex-nowrap align-items-start">
                                                                    <div class="col-5">
                                                                    <select id="service_type" name="service_type"
                                                                                class="form-control  ajax_request service_type"
                                                                                data-post={{$post->id}}>
                                                                            <option value="class" {{ ($post->service_type == 'class') ? "selected" : "" }}>
                                                                            {{ t('class') }}      </option>
                                                                            <option value="appointment" {{ ($post->service_type == 'appointment') ? "selected" : "" }}>
                                                                            {{ t('time_booking') }}
                                                                            </option>
                                                                            <option value="package" {{ ($post->service_type == 'package') ? "selected" : "" }}>
                                                                            {{ t('package') }}
                                                                            </option>
                                                                            <option value="rent" {{ ($post->service_type == 'rent') ? "selected" : "" }}>
                                                                            {{ t('rent') }}
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                   
                                                                    <span class="col-4 toggle-booking" data-post="{{ $post->id }}" data-booking-required="{{ $post->booking_required }}">
                                                                        <div class="icon-text-container">
                                                                            @if($post->booking_required)
                                                                                <i class="material-icons booking-icon">check_box</i>
                                                                                <span>{{ t('deactivate_booking') }}</span>
                                                                            @else
                                                                                <i class="material-icons booking-icon">check_box_outline_blank</i>
                                                                                <span>{{ t('activate_booking') }}</span>
                                                                            @endif
                                                                        </div>
                                                                    </span>
                                                                    <div class="col-2 ajax_save_btn float-right">
                                                                <button id="save-button" class="btn btn-success"
                                                                        disabled>{{ t('save') }}
                                                                </button>
                                                            </div>
                                                                    <div>
                                                                        @php

                                                                            $serviceSettings = view()->shared('account.posts');
                                                                            $helpItems = [];
                                                                            if (!empty($serviceSettings['calendar_help_text'])) {
                                                                                $helpItems[] = [
                                                                                    'heading' => t('calendar'),
                                                                                    'content' => $serviceSettings['calendar_help_text'],
                                                                                    'icon' => 'calendar_today'
                                                                                ];
                                                                            }
                                                                            if (!empty($serviceSettings['time_help_text'])) {
                                                                                $helpItems[] = [
                                                                                    'heading' => t('time_availability'),
                                                                                    'content' => $serviceSettings['time_help_text'],
                                                                                    'icon' => 'access_time'
                                                                                ];
                                                                            }
                                                                            if (!empty($serviceSettings['buffer_help_text'])) {
                                                                                $helpItems[] = [
                                                                                    'heading' => t('buffer'),
                                                                                    'content' => $serviceSettings['buffer_help_text'],
                                                                                    'icon' => 'hourglass_empty'
                                                                                ];
                                                                            }
                                                                            if (!empty($serviceSettings['inventory_help_text'])) {
                                                                                $helpItems[] = [
                                                                                    'heading' => t('inventory'),
                                                                                    'content' => $serviceSettings['inventory_help_text'],
                                                                                    'icon' => 'local_shipping'
                                                                                ];
                                                                            }
                                                                            if (!empty($serviceSettings['cancellation_help_text'])) {
                                                                                $helpItems[] = [
                                                                                    'heading' => t('cancellation'),
                                                                                    'content' => $serviceSettings['cancellation_help_text'],
                                                                                    'icon' => 'block'
                                                                                ];
                                                                            }
                                                                            if (!empty($serviceSettings['package_type_help_text'])) {
                                                                                $helpItems[] = [
                                                                                    'heading' => t('package_type'),
                                                                                    'content' => $serviceSettings['package_type_help_text'],
                                                                                    'icon' => 'diamond'
                                                                                ];
                                                                            }

                                                                            if (!empty($serviceSettings['default_package_text_limit'])) {
                                                                               $wordsLimit=$serviceSettings['default_package_text_limit'];
                                                                               preg_match('/<strong>(\d+)<\/strong>/', $wordsLimit, $matches);
                                                                               $wordsLimit = isset($matches[1]) ? (int)$matches[1] : null;
                                                                            }else{
                                                                                $wordsLimit=1000;
                                                                            }

                                                                        @endphp

                                                                        @include('layouts.inc.HelpSidePannel.helpPannel', ['post' => $post, 'helpItems' => $helpItems])

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="booking-options-container">
                                                            <!-- Visible Icons Initially -->
                                                            <div class="booking-options visible-options ">
                                                           
                                                                <span 
                                                                    class="booking-item text-secondary openModalBtn"
                                                                    id="calendar" 
                                                                    data-post="{{ $post->id }}"
                                                                    tabindex="0"
                                                                    role="button"
                                                                >
                                                                    <div class="icon-text-container ">
                                                                        <i class="material-icons booking-icon d-flex justify-content-end ml-1">calendar_today</i>
                                                                        <span>{{ t('calendar') }}</span>
                                                                    </div>
                                                                </span>
                                                                
                                                                <div class="modal fade " id="optionModal" tabindex="-1" aria-labelledby="optionModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog modal-lg calendarModal">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="optionModalLabel">{{ t('select_date_range') }}</h5>
                                                                                <button type="button" class="close" id="closeBtn" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            
                                                                          
                                                                            <div class="modal-body d-flex flex-row justify-content-between align-items-start flex-wrap">
                                                                            
                                                                            <div id="calendarContainer" class="card col-lg-6 col-md-12 col-sm-12" data-post="{{ $post->id }}">
                                                                                    <h5 class="modal-title pt-4 pb-2" id="optionModalLabel">{{ t('select_dates') }}</h5>
                                                                                    <div class="calendar-card">
                                                                                        <table class="table table-bordered table-sm calendar">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <div class="card-header d-flex justify-content-between align-items-center" id="calendarYear">
                                                                                                        <span id="prev-month" class="btn btn-light btn-sm">
                                                                                                            <i class="material-icons booking-icon op">chevron_left</i>
                                                                                                        </span>
                                                                                                        <span id="month-year-display-1"></span>
                                                                                                        <span id="next-month" class="btn btn-light btn-sm">
                                                                                                            <i class="material-icons booking-icon op">chevron_right</i>
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </tr>
                                                                                                <tr  id="calendarDays">
                                                                                                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                                                                                        <th class="text-center">{{ $day }}</th>
                                                                                                    @endforeach
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody id="calendar-1"></tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Tab Navigation and Content -->
                                                                                <div class="col-lg-6 col-md-12 col-sm-12 p-4 card shadow-sm">
                                                                                           
                                                                                    <div class="card p-3 d-flex flex-row w-5 justify-content-around align-items-center" data-post="{{$post->id}}">
                                                                                        @foreach(['days' => t('daily'), 'week' => t('weekly'), 'month' => t('monthly')] as $value => $label)
                                                                                        <div class="form-check d-flex align-items-center p-3">
                                                                                            <input class="form-check-input me-2 tabOptions" type="radio" name="tabOptions" id="{{ $value }}Option" value="{{ $value }}">
                                                                                            <label class="form-check-label mt-1" for="{{ $value }}Option">
                                                                                                {{ $label }}
                                                                                            </label>
                                                                                        </div>

                                                                                        @endforeach
                                                                                    </div>

                                                                                    <!-- Tab Content -->
                                                                                    <div class="tab-content mt-3 shadow-sm" data-post="{{$post->id}}">
                                                                                    <div class="card p-3 mb-4 shadow-sm">
                                                                                                <label for="startDate" class="form-label">{{ t('start_date') }}:</label>
                                                                                                <input type="date" id="startDate" class="form-control" >
                                                                                            </div>
                                                                                        <!-- Daily Tab -->
                                                                                        <div class="tab-pane fade card bg-white p-4 pt-0 shadow-sm"  id="days" role="tabpanel">
                                                                                            <div class="card p-3 shadow-sm">
                                                                                                @foreach([
                                                                                                    'includeWeekends' => t('repeat_daily_include_weekends'),
                                                                                                    'excludeWeekends' => t('repeat_daily_exclude_weekends')
                                                                                                ] as $value => $label)
                                                                                                <div class="form-check d-flex align-items-center">
                                                                                                    <input class="form-check-input me-2 my-1" type="radio" name="repeatOption" id="repeatDaily{{ ucfirst($value) }}" value="{{ $value }}">
                                                                                                    <label class="form-check-label mt-0 my-1" for="repeatDaily{{ ucfirst($value) }}">
                                                                                                        {{ $label }}
                                                                                                    </label>
                                                                                                </div>

                                                                                                @endforeach
                                                                                            </div>
                                                                                   

                                                                                            <div class="card mt-4 shadow-sm">
                                                                                                <div class="card-body">
                                                                                                    <h5 class="card-title">{{ t('end_repeat_options') }}</h5>
                                                                                                    @foreach([
                                                                                                        'neverDays' => t('never_stop'),
                                                                                                        'untilDateDays' => t('run_until_date'),
                                                                                                        'untilOccurrencesDays' => t('repeat_until_occurrences')
                                                                                                    ] as $value => $label)
                                                                                                        <div class="form-check mt-2 ">
                                                                                                            
                                                                                                        <label class="form-check-label d-flex align-items-center" for="{{ $value }}Days">
                                                                                                            <input class="form-check-input me-2 mb-1" type="radio" name="endRepeatOptionsDays" id="{{ $value }}" value="{{ $value }}" checked>
                                                                                                            {{ $label }}
                                                                                                        </label>


                                                                                                            @if($value === 'untilDateDays')
                                                                                                                <input type="date" id="endDateDays" class="form-control mt-2" value="" >
                                                                                                            @elseif($value === 'untilOccurrencesDays')
                                                                                                                <input type="number" id="occurrencesNumberDays" class="form-control mt-2" placeholder="{{ t('enter_number_occurrences') }}" min="1" >
                                                                                                            @endif
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>

                                                                                        <!-- Weekly Tab -->
                                                                                        <div class=" card bg-white p-4 tab-pane fade shadow-sm" id="week" role="tabpanel">
                                                                                            <!-- Inline Day Selection -->
                                                                                            <div class="card p-4 mb-4 px-3 shadow-sm">
                                                                                                <label class="form-label">{{ t('select_days_of_week') }}:</label>
                                                                                                <!-- Bootstrap grid to manage the layout -->
                                                                                                <div class="row">
                                                                                                    @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $index => $day)
                                                                                                        <div class="col-3 mb-2">
                                                                                                            <div class="form-check d-flex align-items-center">
                                                                                                                
                                                                                                                <label class="form-check-label mb-0 d-flex align-items-center justify-content-between " for="{{ strtolower($day) }}">
                                                                                                                    {{ substr($day, 0, 3) }}
                                                                                                                    <input class="form-check-input me-2 mb-1 weekDayBox" name="weekDays" type="checkbox" id="{{ strtolower($day) }}" value="{{ $day }}">
                                                                                                                </label>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="card mt-4 p-3 shadow-sm">
                                                                                                <div class="card-body">
                                                                                                    <h5 class="card-title">{{ t('end_repeat_options') }}</h5>
                                                                                                    @foreach([
                                                                                                            'neverWeek' => t('never_stop'),
                                                                                                            'untilDateWeek' => t('run_until_date'),
                                                                                                            'untilOccurrencesWeek' => t('repeat_until_occurrences')
                                                                                                        ] as $value => $label)
                                                                                                            <div class="form-check mt-2">
                                                                                                                
                                                                                                                <label class="form-check-label d-flex align-items-center justify-content-between" for="{{ $value }}">
                                                                                                                    {{ $label }}
                                                                                                                    <input class="form-check-input mb-1" type="radio" name="endRepeatOptionsWeek" id="{{ $value }}" value="{{ $value }}">
                                                                                                                </label>

                                                                                                                @if($value === 'untilDateWeek')
                                                                                                                    <input type="date" id="endDateWeek" class="form-control mt-2" disabled>
                                                                                                                @elseif($value === 'untilOccurrencesWeek')
                                                                                                                    <input type="number" id="occurrencesNumberWeek" class="form-control mt-2" placeholder="{{ t('enter_number_occurrences') }}" min="1" disabled>
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        @endforeach

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                  <!-- Monthly Tab -->
                                                                                        <div class="tab-pane fade " id="month" role="tabpanel">
                                                                                            <div class="card p-3 shadow-sm">
                                    
                                                                                                <!-- End Repeat Options -->
                                                                                                <div class="card mt-4 shadow-sm">
                                                                                                    <div class="card-body">
                                                                                                        <h5 class="card-title">{{ t('end_repeat_options') }}</h5>
                                                                                                        
                                                                                                        @foreach([
                                                                                                            'neverMonth' => t('never_stop'),
                                                                                                            'untilDateMonth' => t('run_until_date'),
                                                                                                            'untilOccurrencesMonth' => t('repeat_until_occurrences')
                                                                                                        ] as $value => $label)
                                                                                                            <div class="form-check mt-2 align-items-center">
                                                                                                               
                                                                                                                <label class="form-check-label d-flex align-items-around flex-row justify-content-between" for="{{ $value }}Month">{{ $label }}
                                                                                                                <input class="form-check-input" type="radio" name="endRepeatOptionsMonth" id="{{$value}}" value="{{ $value }}" >
                                                                                                                </label>

                                                                                                                <!-- End Date Input -->
                                                                                                                @if($value === 'untilDateMonth')
                                                                                                                    <input type="date" id="endDateMonth" class="form-control mt-2"  >
                                                                                                                <!-- Occurrences Input -->
                                                                                                                @elseif($value === 'untilOccurrencesMonth')
                                                                                                                    <input type="number" id="occurrencesNumberMonth" class="form-control mt-2" placeholder="{{ t('enter_number_occurrences') }}" min="1" >
                                                                                                                @endif
                                                                                                            </div>
                                                                                                        @endforeach
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xl-12 py-3">
                                                                                            <div id="successAlert" class="alert alert-success alert-dismissible fade p-3" role="alert" style="display: none;">
                                                                                                Options saved successfully!
                                                                                            </div>

                                                                                            <div id="errorAlert" class="alert alert-danger alert-dismissible fade p-3" role="alert" style="display: none;">
                                                                                                An error occurred while saving options.
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                            <button type="button" class="close btn btn-secondary p-2 m-2" id="cancelBtn" data-dismiss="modal" aria-label="Close">
                                                                            {{ t('close') }}
                                                                                </button>
                                                                                <button type="button" class="btn btn-primary" id="saveOptions">{{ t('save') }}</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

 
                                                                <span id="time-availability" 
                                                                    class="booking-item text-secondary time-availability" 
                                                                    data-post="{{ $post->id }}" 
                                                                >
                                                                    <div class="icon-text-container">
                                                                        <i class="material-icons booking-icon">access_time</i>
                                                                        <span>{{ t('time_availability') }}</span>
                                                                    </div>
                                                                </span>

                                                                <!-- Modal HTML Structure -->
                                                                <div id="slotDetailsModal" class="modal fade" tabindex="-1" role="dialog" data-post="{{ $post->id }}">
                                                                    <div class="modal-dialog modal-lg" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title">{{ t('time_availability_details') }}</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <!-- Status Selection -->
                                                                                <div class="form-group mt-3">
                                                                                    <label>{{ t('status') }}:</label>
                                                                                    <div class="d-flex justify-content-around align-items-center">
                                                                                        <label class="me-3 d-flex align-items-center justify-content-around">
                                                                                            <input type="radio" id="open" name="slot-status" value="open">
                                                                                            <span class="ms-2 mx-1">{{ t('open_with_main_hours') }}</span>
                                                                                        </label>
                                                                                        <label class="me-3 d-flex align-items-center justify-content-around">
                                                                                            <input type="radio" id="temp_closed" name="slot-status" value="temp_closed">
                                                                                            <span class="ms-2 mx-1">{{ t('temp_closed') }}</span>
                                                                                        </label>
                                                                                        <label class="me-3  d-flex align-items-center justify-content-around">
                                                                                            <input type="radio" id="perm_closed" name="slot-status" value="perm_closed">
                                                                                            <span class="ms-2 mx-1">{{ t('perm_closed') }}</span>
                                                                                        </label>
                                                                                    </div>

                                                                                </div>

                                                                                <!-- Accordion Container -->
                                                                                <div id="accordion-container"></div>
                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary" id="save-slot-btn">{{ t('save') }}</button>
                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('close') }}</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>



                                                            <span class="booking-item text-secondary buffer" id="buffer" data-post="{{ $post->id }}" data-value="{{$post->buffer_time}}">
                                                                <div class="icon-text-container">
                                                                    <i class="material-icons booking-icon">hourglass_empty</i>
                                                                    <span>{{ t('buffer') }}</span>
                                                                </div>
                                                            </span>
                                                            <!-- Time duration -->
                                                            <span class="booking-item text-secondary duration" id="duration" data-post="{{ $post->id }}" data-type="duration" data-label="{{ t('Set Duration') }}" data-value="{{$post->duration}}">
                                                                <div class="icon-text-container">
                                                                    <i class="material-icons booking-icon">timer</i>
                                                                    <span>{{ t('duration_text') }}</span>
                                                                </div>
                                                            </span>

                                                            <span class="booking-item text-secondary slots" id="slots" data-post={{ $post->id }}>
                                                                <div class="icon-text-container">
                                                                    <i class="material-icons booking-icon">local_shipping</i>
                                                                    <span>{{ t('inventory') }}</span>
                                                                </div>
                                                            </span>
                                                            <span class="booking-item text-secondary cancellation" id="cancellation" data-post={{ $post->id }}>
                                                                <div class="icon-text-container">
                                                                    <i class="material-icons booking-icon">block</i>
                                                                    <span>{{ t('cancellation') }}</span>
                                                                </div>
                                                            </span>

                                                            <div class="modal fade" id="dynamicModal" tabindex="-1" role="dialog" aria-labelledby="dynamicModalLabel" aria-hidden="true"  >
    <div class="modal-dialog modal-dialog-centered modal-dialogs modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dynamicModalLabel">{{ t('Dynamic Modal') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>  
            <div class="modal-body">
                <!-- Dynamic content will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Close') }}</button>
                <button type="button" class="btn btn-primary modal-save-btn">{{ t('save') }}</button>
            </div>
        </div>
    </div>
</div>


                                                                <div class="booking-item hidden-options">
                                                                    <span class="booking-item text-secondary" id="package-type">
                                                                        <div class="icon-text-container" >
                                                                          <i class="material-icons">layers</i>    
                                                                            <span>{{ t('package_type') }}</span>
                                                                        </div>
                                                                    </span>
                                                                </div>
                                                                
                                                            </div>

                                                            <!-- Toggle Button -->
                                                            <span class="toggle-icons">
                                                                <i class="material-icons toggle-arrow">expand_more</i>
                                                                </span>
                                                        </div>

                                                         
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endif
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>

                        <nav>
                            {{ isset($posts) ? $posts->links() : '' }}
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    <style>
        .action-td p {
            margin-bottom: 5px;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.toggle-icons').on('click', function () {
                $('.hidden-options').toggle(1000); 
                const $icon = $(this).find('.toggle-arrow');
                if ($icon.text() === 'expand_more') {
                    $icon.text('expand_less'); 
                } else {
                    $icon.text('expand_more'); 
                }
            });
           
        });
    </script>
    <script type="text/javascript">
            $(function() {
                $('#addManageTable').footable().bind('footable_filtering', function(e) {
                    var selected = $('.filter-status').find(':selected').text();
                    if (selected && selected.length > 0) {
                        e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                        e.clear = !e.filter;
                    }
                });

                $('.clear-filter').click(function(e) {
                    e.preventDefault();
                    $('.filter-status').val('');
                    $('table.demo').trigger('footable_clear_filter');
                });

                $('#checkAll').click(function() {
                    checkAll(this);
                });

                $('a.delete-action, button.delete-action, a.confirm-action').click(function(e) {
                    e.preventDefault(); 
                    var confirmation = confirm("{{ t('confirm_this_action') }}");

                    if (confirmation) {
                        if ($(this).is('a')) {
                            var url = $(this).attr('href');
                            if (url !== 'undefined') {
                                redirect(url);
                            }
                        } else {
                            $('form[name=listForm]').submit();
                        }

                    }

                    return false;
                });
            });
    </script>
 <script>
    $(document).ready(function () {
        const $modal = $('#dynamicModal'); 
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let postId;
        
        function sendAjaxRequest(data) {
            $.ajax({
                url: "{{ route('update-post-ajax') }}", 
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    console.log(`${data.updateParam.replace('_', ' ')} {{ t('updated_successfully') }}.`, response);
                    $modal.modal('hide');
                },
                error: function (xhr, status, error) {
                    console.error(`{{ t('error_storing') }} ${data.updateParam.replace('_', ' ')}: ${status} - ${error}`);
                }
            });
        }

        function fetchData(postId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('fetch-post-data') }}",  
                    type: 'POST',
                    data: {
                        postId: postId,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        console.log(response);
                        resolve(response); 
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', error);
                        reject(error); 
                    }
                });
            });
        }

        function showDynamicModal(title, content, updateParam, initialValue) {
            $modal.find('.modal-title').text(title);
            $modal.find('.modal-body').html(content);
            $modal.find('.modal-save-btn').off('click').on('click', function () {
                let value = $modal.find('.modal-input').val().trim();

                if (updateParam === 'buffer_time' && (isNaN(value) || value < 0)) {
                    alert("{{ t('buffer_time_must_be_non_negative') }}");
                    return;
                }
                if (updateParam === 'cancellation_reason' && !value) {
                    alert("{{ t('cancellation_reason_cannot_be_empty') }}");
                    return;
                }
                if (updateParam === 'slot_details' && !value) {
                    alert("{{ t('slot_information_cannot_be_empty') }}");
                    return;
                }
                if(updateParam === 'slot_details' && value > 10){
                    alert("{{ t('slot_max_value') }}");
                    return;
                }

                sendAjaxRequest({
                    updateParam,
                    paramValue: value,
                    postId: postId, 
                    _token: csrfToken
                });

                $modal.modal('hide');
            });
            $modal.modal('show');
        }

        $('.buffer').on('click', function () {
            postId = $(this).data('post');

            fetchData(postId)
                .then(response => {
                    const bufferTime = response.selectedValue.bookingdata.buffer_time || 0;
                    const content = `
                        <div class="btn-group" role="group" aria-label="{{ t('quick_buffer_time_options') }}">
                            <button type="button" class="btn btn-secondary buffer-time-btn" data-time="15">15 {{ t('minutes') }}</button>
                            <button type="button" class="btn btn-secondary buffer-time-btn" data-time="30">30 {{ t('minutes') }}</button>
                            <button type="button" class="btn btn-secondary buffer-time-btn" data-time="60">1 {{ t('hour') }}</button>
                        </div>
                        <div class="form-group mt-2">
                            <input type="number" class="form-control modal-input" value="${bufferTime}" placeholder="{{ t('enter_buffer_time') }} ({{ t('minutes') }})">
                        </div>`;

                    showDynamicModal("{{ t('set_buffer_time') }}", content, 'buffer_time', bufferTime);

                    $modal.find('.buffer-time-btn').on('click', function () {
                        const selectedTime = $(this).data('time');
                        $modal.find('.modal-input').val(selectedTime);
                    });
                })
                .catch(error => {
                    console.error('Error fetching buffer time:', error);
                });
        });
       $('.duration').on('click', function() {
    const postId = $(this).data('post');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    function initTooltips() {
        $('[data-bs-toggle="tooltip"]').each(function() {
            if (this._tippy) {
                this._tippy.destroy();
                delete this._tippy;
            }
            if (this._tooltip) {
                this._tooltip.dispose();
                delete this._tooltip;
            }
        });
        
        $('[data-bs-toggle="tooltip"]').each(function() {
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    this._tooltip = new bootstrap.Tooltip(this, {
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body',
                        boundary: 'window',
                        animation: true,
                        delay: { "show": 300, "hide": 100 }
                    });
                }
                else if ($.fn.tooltip) {
                    $(this).tooltip({
                        trigger: 'hover',
                        placement: 'top',
                        container: 'body',
                        delay: { "show": 300, "hide": 100 }
                    });
                }
            } catch (e) {
                console.error('Tooltip init error:', e);
            }
        });
    }

    function setupModalTooltips() {
        $('#dynamicModal').on('scroll', function() {
            $('[data-bs-toggle="tooltip"]').each(function() {
                if (this._tooltip) {
                    this._tooltip.update();
                }
            });
        });
        
        $('#dynamicModal').on('hide.bs.modal', function() {
            $('[data-bs-toggle="tooltip"]').each(function() {
                if (this._tooltip) {
                    this._tooltip.hide();
                }
            });
        });
    }

    function initializeTimepickers() {
        $('.open-time').each(function() {
            const $input = $(this);
            const durationValue = $input.data('duration-value');
            
            $input.datetimepicker({
                format: 'HH:mm',
                icons: {
                    time: 'fas fa-clock',
                    up: 'fa fa-angle-up',
                    down: 'fa fa-angle-down'
                },
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day'),
                stepping: 15,
                useCurrent: false
            });
        });
    }

    function sendAjaxRequest(data) {
        $.ajax({
            url: "{{ route('update-durations-ajax') }}",
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function() {
                $('#dynamicModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                let errorMsg = "{{ t('error_saving_durations') }}";
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    errorMsg += "\n\n" + Object.values(xhr.responseJSON.errors).join("\n");
                }
                alert(errorMsg);
            }
        });
    }

    function fetchData(postId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "{{ route('fetch-post-data') }}",
                type: 'POST',
                data: { postId: postId, _token: csrfToken },
                success: resolve,
                error: function(xhr) {
                    let errorMsg = "{{ t('error_loading_data') }}";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg += "\n\n" + xhr.responseJSON.message;
                    }
                    reject(errorMsg);
                }
            });
        });
    }

    function convertToMinutes(value, unit) {
        const conversions = {
            minutes: 1, hours: 60, days: 1440, 
            weeks: 10080, months: 43200, years: 525600
        };
        return value * (conversions[unit] || 1);
    }

    function convertFromMinutes(minutes) {
        const units = [
            {value: 525600, unit: 'years'}, {value: 43200, unit: 'months'},
            {value: 10080, unit: 'weeks'}, {value: 1440, unit: 'days'},
            {value: 60, unit: 'hours'}, {value: 1, unit: 'minutes'}
        ];
        for (const {value, unit} of units) {
            if (minutes % value === 0) return {unit, value: minutes/value};
        }
        return {unit: 'minutes', value: minutes};
    }

    function getDurationLabel(value) {
        const labels = {
            30: "{{ t('30_minutes') }}", 60: "{{ t('1_hour') }}",
            360: "{{ t('6_hours') }}", 720: "{{ t('12_hours') }}",
        };
        return labels[value] || value;
    }

    const durationTranslations = {
        minutes: "{{ t('minutes') }}", hours: "{{ t('hours') }}",
    };

    function validateRow($row) {
        const $title = $row.find('.duration-title');
        const $durationSelect = $row.find('.duration-select');
        const $durationValue = $row.find('.duration-value');
        const $durationUnit = $row.find('.duration-unit');
        const $openTime = $row.find('.open-time');
        
        const titleVal = $title.val().trim();
        const durationSelectVal = $durationSelect.val();
        const isCustom = durationSelectVal === 'custom';
        const durationValue = parseInt($durationValue.val()) || 0;
        
        $title.removeClass('is-invalid');
        $durationSelect.removeClass('is-invalid');
        $durationValue.removeClass('is-invalid');
        $durationUnit.removeClass('is-invalid');
        $openTime.removeClass('is-invalid');
        
        let isValid = true;
        
        if (!titleVal) {
            $title.addClass('is-invalid');
            isValid = false;
        }
        if (!durationSelectVal) {
            $durationSelect.addClass('is-invalid');
            isValid = false;
        }
        
        if (isCustom) {
            if (durationValue <= 0) {
                $durationValue.addClass('is-invalid');
                isValid = false;
            }
            if (!$durationUnit.val()) {
                $durationUnit.addClass('is-invalid');
                isValid = false;
            }
        }
        
        return isValid;
    }

    function buildDurationRow(duration = {}, shippingAddresses = [], timeRanges = [], isMainRow = false) {
       const standardDurations = ['30', '60', '360', '720'];
    const dbValue = duration?.duration_value || duration?.value;
    const isCustom = dbValue ? !standardDurations.includes(String(dbValue)) : false;
    const converted = isCustom ? convertFromMinutes(parseInt(dbValue || 0)) : null;
    timeRanges = Array.isArray(timeRanges) ? timeRanges : [];
    
   const matchingTimeRange = Array.isArray(timeRanges) ? 
    timeRanges.find(range => range && range.duration_id == duration.id) : 
    null;

    
    const openTime = duration?.open_time || matchingTimeRange?.open_time || '';
    
    let selectedAddress = null;
    
    if (duration?.location_id) {
        selectedAddress = shippingAddresses.find(addr => addr.id == duration.location_id);
    }
    
    if (!selectedAddress) {
        selectedAddress = shippingAddresses.find(addr => addr.default_address == true);
    }
    
    if (!selectedAddress && shippingAddresses.length > 0) {
        selectedAddress = shippingAddresses[0];
    }

    let shippingSelect = '';
    if (shippingAddresses.length > 0) { 
        shippingSelect = `
            <div class="col-md-3 col-12 mb-2">
                <label class="form-label m-0">
                    {{ t('shipping_address_label') }}
                    <i class="fas fa-question-circle ms-1" 
                       data-bs-toggle="tooltip" 
                       data-bs-html="true"
                       title="{{ t('select_shipping_address_help') }}"></i>
                </label>
                <select class="form-control shipping-select" ${shippingAddresses.length === 1 ? 'disabled' : ''}>
                    ${shippingAddresses.map(addr => {
                        const isSelected = selectedAddress && addr.id == selectedAddress.id;
                        return `
                            <option value="${addr.id}" 
                                data-title="${addr.address_title || ''}"
                                ${isSelected ? 'selected' : ''}>
                                ${addr.address_title || 'Untitled Address'}
                            </option>`;
                    }).join('')}
                </select>
                ${shippingAddresses.length === 1 ? `<input type="hidden" class="shipping-select" value="${shippingAddresses[0].id}">` : ''}
            </div>`;
    }

    return `
        <div class="row align-items-center mt-2 duration-row" data-duration-id="${duration?.id || ''}">
            <div class="${shippingAddresses.length > 0 ? 'col-md-2' : 'col-md-3'} col-12 mb-2">
                <label class="form-label m-0">
                    {{ t('duration_title_label') }}
                    <i class="fas fa-question-circle ms-1" 
                       data-bs-toggle="tooltip" 
                       data-bs-html="true"
                       title="{{ t('duration_title_help') }}"></i>
                </label>
                <input type="text" class="form-control duration-title" 
                       value="${duration?.duration_title || duration?.title || ''}" 
                       placeholder="{{ t('duration_title') }}" required>
                <div class="invalid-feedback">{{ t('duration_title_required') }}</div>
            </div>

            <div class="${shippingAddresses.length > 0 ? 'col-md-2' : 'col-md-2'} col-12 mb-2">
                <label class="form-label m-0">
                    {{ t('Open At') }}
                    <i class="fas fa-question-circle ms-1" 
                       data-bs-toggle="tooltip" 
                       data-bs-html="true"
                       title="{{ t('open_at_help') }}"></i>
                </label>
                    <input type="text" class="form-control open-time" 
                           value="${openTime}" 
                           placeholder="{{ t('open_time') }}">
                </div>
                <div class="${shippingAddresses.length > 0 ? 'col-md-2' : 'col-md-3'} col-12 mb-2">
                    <label class="form-label m-0">
                        {{ t('duration_label') }}
                        <i class="fas fa-question-circle ms-1" 
                           data-bs-toggle="tooltip" 
                           data-bs-html="true"
                           title="{{ t('duration_type_help') }}"></i>
                    </label>
                    <select class="form-control duration-select" required>
                        <option value="">{{ t('select_duration') }}</option>
                        ${standardDurations.map(val => `
                            <option value="${val}" ${(dbValue == val) ? 'selected' : ''}>
                                ${getDurationLabel(val)}
                            </option>
                        `).join('')}
                        <option value="custom" ${isCustom ? 'selected' : ''}>Custom</option>
                    </select>
                    <div class="invalid-feedback">{{ t('duration_type_required') }}</div>
                </div>

                ${shippingSelect}

                <div class="${shippingAddresses.length > 0 ? 'col-md-2' : 'col-md-3'} col-12 mb-2">
                    <label class="form-label m-0">
                        {{ t('inventory_label') }}
                        <i class="fas fa-question-circle ms-1" 
                           data-bs-toggle="tooltip" 
                           data-bs-html="true"
                           title="{{ t('inventory_help') }}"></i>
                    </label>
                    <input type="number" class="form-control inventory" 
                           value="${duration?.max_capacity || duration?.inventory || 0}" 
                           min="0" placeholder="{{ t('inventory') }}" required>
                </div>

                ${isMainRow ? '' : `
                <div class="col-md-1 col-12 mb-2">
                    <label class="form-label d-block">&nbsp;</label>
                    <button class="btn btn-danger btn-sm remove-duration">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>`}

                <div class="col-12 custom-fields" style="display: ${isCustom ? 'block' : 'none'}">
                    <div class="row mt-2">
                        <div class="col-6">
                            <label class="form-label m-0">
                                {{ t('duration_unit') }}
                                <i class="fas fa-question-circle ms-1" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-html="true"
                                   title="{{ t('duration_unit_help') }}"></i>
                            </label>
                            <select class="form-control duration-unit" ${isCustom ? 'required' : ''}>
                                ${['minutes', 'hours', 'days', 'weeks', 'months', 'years'].map(unit => `
                                    <option value="${unit}" ${converted?.unit === unit ? 'selected' : ''}>
                                        ${durationTranslations[unit]}
                                    </option>
                                `).join('')}
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label m-0">
                                {{ t('duration_value') }}
                                <i class="fas fa-question-circle ms-1" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-html="true"
                                   title="{{ t('duration_value_help') }}"></i>
                            </label>
                            <input type="number" class="form-control duration-value" 
                                   value="${converted?.value || 1}" min="1" ${isCustom ? 'required' : ''}>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    fetchData(postId)
        .then(response => {
            const { shipping_addresses = [], postDurations = [], time_range = {} } = response.selectedValue || {};
            const timeRanges = time_range?.slots?.flatMap(slot => slot.time_ranges) || [];
            
            let content = `
                <div class="duration-form-container">
                    <div id="durations-container">
                        ${postDurations.length > 0 ? 
                            postDurations.map((dur, i) => 
                                buildDurationRow(dur, shipping_addresses, timeRanges, i === 0)).join('') :
                            buildDurationRow({}, shipping_addresses, timeRanges, true)}
                    </div>
                    <div id="duplicate-error" class="alert alert-danger d-none mb-2"></div>
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="add-duration-btn">
                        {{ t('add_new_duration') }}
                    </button>
                </div>`;

            showDynamicModal("{{ t('duration_text') }}", content, 'duration');

            $('#dynamicModal').on('shown.bs.modal', function() {
                setTimeout(() => {
                    initTooltips();
                    setupModalTooltips();
                    initializeTimepickers();
                }, 50);
            });

            $('#add-duration-btn').click(function() {
                const timeRanges = $('.duration-row').length > 0 ? 
                    $('.duration-row').first().data('time-ranges') || [] : 
                    [];
                
                $('#durations-container').append(buildDurationRow({}, shipping_addresses, timeRanges, false));
                setTimeout(() => {
                    initTooltips();
                    initializeTimepickers();
                }, 50);
            });

            $('#dynamicModal')
                .on('click', '.remove-duration', function() {
                    if ($('.duration-row').length > 1) {
                        $(this).closest('.duration-row').remove();
                    } else {
                        alert("{{ t('must_have_one_duration') }}");
                    }
                })
                .on('change', '.duration-select', function() {
                    const $row = $(this).closest('.duration-row');
                    const isCustom = $(this).val() === 'custom';
                    $row.find('.custom-fields').toggle(isCustom);
                    
                    $row.find('.duration-unit').prop('required', isCustom);
                    $row.find('.duration-value').prop('required', isCustom);
                });

            $('.modal-save-btn').off('click').click(function() {
                const durations = [];
                let isValid = true;
                let duplicateFound = false;
                
                $('#duplicate-error').addClass('d-none');
                $('.is-invalid').removeClass('is-invalid');
                $('.is-invalid-duplicate').removeClass('is-invalid-duplicate');
                const combinations = new Set();
                let duplicateRows = [];
                
                $('.duration-row').each(function() {
                    const $row = $(this);
                    const getVal = (sel) => $row.find(sel).val();
                    const $shipping = $row.find('.shipping-select');
                    
                    const durationData = {
                        duration_title: getVal('.duration-title').trim(),
                        duration_select: getVal('.duration-select'),
                        duration_value: getVal('.duration-select') === 'custom' ? 
                            parseInt(getVal('.duration-value') || 1) : 0,
                        duration_unit: getVal('.duration-select') === 'custom' ? 
                            getVal('.duration-unit') : null,
                        location_id: $shipping.length ? $shipping.val() : null,
                        open_time: getVal('.open-time')
                    };
                    
                    if (!durationData.duration_title || !durationData.duration_select) {
                        return true; 
                    }
                    
                    const durationValue = durationData.duration_select === 'custom' ? 
                        convertToMinutes(durationData.duration_value, durationData.duration_unit) :
                        parseInt(durationData.duration_select);
                    
                    const combinationKey = `${durationData.duration_title}_${durationValue}_${durationData.location_id || 'no-address'}_${durationData.open_time || 'no-time'}`;
                    
                    if (combinations.has(combinationKey)) {
                        duplicateFound = true;
                        duplicateRows.push($row);
                    } else {
                        combinations.add(combinationKey);
                    }
                });

                if (duplicateFound) {
                    $('#duplicate-error')
                        .removeClass('d-none')
                        .text("{{ t('duplicate_duration_combination') }}");
                    
                    duplicateRows.forEach($row => {
                        $row.find('.duration-title').addClass('is-invalid-duplicate');
                        $row.find('.duration-select').addClass('is-invalid-duplicate');
                        if ($row.find('.shipping-select').length) {
                            $row.find('.shipping-select').addClass('is-invalid-duplicate');
                        }
                        $row.find('.open-time').addClass('is-invalid-duplicate');
                        $row[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                    
                    return;
                }

                $('.duration-row').each(function() {
                    const $row = $(this);
                    if (!validateRow($row)) {
                        isValid = false;
                        $row[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        return false; 
                    }
                });

                if (!isValid) return;
                $('.duration-row').each(function() {
                    const $row = $(this);
                    const getVal = (sel) => $row.find(sel).val();
                    const $shipping = $row.find('.shipping-select');
                    
                    const durationData = {
                        duration_title: getVal('.duration-title').trim(),
                        duration_select: getVal('.duration-select'),
                        duration_value: getVal('.duration-select') === 'custom' ? 
                            parseInt(getVal('.duration-value') || 1) : 0,
                        duration_unit: getVal('.duration-select') === 'custom' ? 
                            getVal('.duration-unit') : null,
                        location_id: $shipping.length ? $shipping.val() : null,
                        open_time: getVal('.open-time')
                    };
                    const durationValue = durationData.duration_select === 'custom' ? 
                        convertToMinutes(durationData.duration_value, durationData.duration_unit) :
                        parseInt(durationData.duration_select);
                    
                    durations.push({
                        id: $row.data('duration-id'),
                        duration_title: durationData.duration_title,
                        duration_value: durationValue,
                        max_capacity: parseInt(getVal('.inventory')) || 0,
                        location_id: durationData.location_id,
                        duration_unit: durationData.duration_unit,
                        open_time: durationData.open_time,
                        post_id: postId
                    });
                });

                if (isValid) {
                    sendAjaxRequest({ postId, durations });
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error);
        });
});

        $('.cancellation').on('click', function () {
            postId = $(this).data('post');

            fetchData(postId)
                .then(response => {
                    const cancellationReason = response.selectedValue.bookingdata.cancellation_reason || '';
                    const content = `
                        <div class="form-group">
                            <input type="text" class="form-control modal-input" value="${cancellationReason}" placeholder="{{ t('enter_cancellation_reason') }}">
                        </div>`;

                    showDynamicModal("{{ t('set_cancellation_reason') }}", content, 'cancellation_reason', cancellationReason);
                })
                .catch(error => {
                    console.error('Error fetching cancellation reason:', error);
                });
        });

        $('.slots').on('click', function () {
            postId = $(this).data('post');

            fetchData(postId)
                .then(response => {
                    const slotDetails = response.selectedValue.bookingdata.slot_details || '';
                    const content = `
                        <div class="form-group">
                            <input type="number" class="form-control modal-input" value="${slotDetails}"  min="1" 
                        max="1000"  placeholder="{{ t('set_slot_information') }}">
                        </div>`;

                    showDynamicModal("{{ t('set_slot_information') }}", content, 'slot_details', slotDetails);
                })
                .catch(error => {
                    console.error('Error fetching slot details:', error);
                });
        });

        $('.service_type').change(function () {
            const $this = $(this);
            const selectedType = $this.val();
            const postId = $this.data('post');

            const iconMapping = {
                'class': ['calendar', 'time-availability', 'buffer', 'slots', 'cancellation'],
                'time-booking': ['calendar', 'time-availability', 'buffer', 'cancellation'],
                'package': ['slots', 'package-type'],
                'rent': ['calendar', 'time-availability', 'buffer', 'slots', 'cancellation']
            };

            toggleIconsForPost(iconMapping[selectedType] || [], postId);
        }).trigger('change');

        function toggleIconsForPost(activeIcons, postId) {
            const allIcons = ['calendar', 'time-availability', 'buffer', 'slots', 'cancellation', 'package-type'];

            allIcons.forEach(icon => {
                const $element = $(`[id="${icon}"][data-post="${postId}"]`); 
                if (activeIcons.includes(icon)) {
                    $element.css('opacity', '1').css('pointer-events', 'auto');
                } else {
                    $element.css('opacity', '0.5').css('pointer-events', 'none');
                }
            });
        }

        $('.ajax_request').change(function (e) {
            e.preventDefault();
            const $this = $(this);
            const updateParam = $this.attr('id') === 'service_type' ? 'service_type' : 'duration';

            sendAjaxRequest({
                updateParam,
                paramValue: $this.val(),
                postId: $this.data("post"),
                _token: csrfToken
            });
        });

        $('.toggle-booking').click(function (e) {
            e.preventDefault();
            let $this = $(this);
            const postId = $this.data('post');
            let bookingRequired = $this.data('booking-required') ? 0 : 1;
            $this.data('booking-required', bookingRequired)
                .find('i.material-icons').text(bookingRequired ? 'check_box' : 'check_box_outline_blank')
                .siblings('span').text(bookingRequired ? '{{ t('deactivate_booking') }}' : '{{ t('activate_booking') }}');

            sendAjaxRequest({
                updateParam: 'booking_required',
                paramValue: bookingRequired,
                postId: postId,
                _token: csrfToken
            });

            return false;
        });
    });
</script>


    <script>
            function checkAll(bx) {
                var chkinput = document.getElementsByTagName('input');
                for (var i = 0; i < chkinput.length; i++) {
                    if (chkinput[i].type == 'checkbox') {
                        chkinput[i].checked = bx.checked;
                    }
                }
            }
    </script>




<script>
$(document).ready(function () {
    const $timeAvailability = $('.time-availability');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    let postId;
    let postDurations = [];
    let shippingAddresses = [];

    const daysOfWeek = [
        "{{ t('Monday') }}", 
        "{{ t('Tuesday') }}", 
        "{{ t('Wednesday') }}",
        "{{ t('Thursday') }}", 
        "{{ t('Friday') }}", 
        "{{ t('Saturday') }}", 
        "{{ t('Sunday') }}"
    ];
    function recalculateAllCloseTimes() {
        $('.time-range').each(function() {
            const $row = $(this);
            const openTime = $row.find('.open-time').val();
            const durationValue = $row.find('.open-time').data('duration-value');
            if (openTime && durationValue) {
                const closeTime = calculateCloseTime(openTime, durationValue);
                $row.find('.close-time').val(closeTime);
            }
        });
    }
    function createAccordion(slots) {
        const accordionContainer = $('#accordion-container').empty();
        daysOfWeek.forEach((day, index) => {
            const slot = (Array.isArray(slots) ? slots.find(slot => slot.day_index === index) : {}) || {};
            accordionContainer.append(generateAccordionPanel(day, index, slot));
        });
        applyAccordionBehavior();
        initializeTimepickers();
        recalculateAllCloseTimes();
        
        if ($('#validation-message').length === 0) {
            $('.modal-footer').before('<div id="validation-message" class="mb-3"></div>');
        }
    }
    
    function generateAccordionPanel(day, dayKey, slot) {
        const isDisabled = slot.disabled || false;
        return `
            <span class="accordion d-flex align-items-center justify-content-between" data-day-key="${dayKey}">
                ${day} <i class="material-icons">keyboard_arrow_down</i>
            </span>
            <div class="panel" data-slot-data='${JSON.stringify(slot)}'>
                <div class="form-check">
                    <label class="form-check-label d-flex align-items-center justify-content-between" for="disable-${dayKey}">
                        ${isDisabled ? '{{ t("Enable All") }}' : '{{ t("Disable All") }}'}
                        <input class="form-check-input toggle-all-time-ranges mb-1" type="checkbox" id="disable-${dayKey}" ${isDisabled ? 'checked' : ''} />
                    </label>
                </div>
                <div id="time-ranges-container-${dayKey}">
                    ${generateTimeRange(isDisabled, slot.time_ranges || [], dayKey)}
                </div>
                <div class="time-range-error text-danger mt-2" style="display: none;"></div>
            </div>
        `;
    }
    function generateTimeRange(isDisabled, timeRanges, dayKey) {
    return postDurations.map((duration, index) => {
        const range = Array.isArray(timeRanges) ? 
            timeRanges.find(r => r && r.duration_id == duration.id) || {} : {};
        
        const location = shippingAddresses.find(addr => addr.id == duration.location_id);

        const openTime = range.open_time || duration.open_time || '';
        const closeTime = calculateCloseTime(openTime, duration.duration_value);
        
        return `
        <div class="form-group mt-2 time-range" data-duration-id="${duration.id}">
            <div class="row align-items-center">
                <div class="col-md-1">
                    <div class="form-check">
                        <input class="form-check-input enable-row" type="checkbox" ${(range.enabled === false) ? '' : 'checked'} data-index="${index}" />
                    </div>
                </div>
                <div class="col-md-2">
                    <label>{{ t('Open At') }}:</label>
                    <div class="d-flex flex-row align-items-center">
                        <div class="input-icon">
                            <i class="material-icons fs-5">schedule</i>
                            <input type="text" class="form-control open-time" 
                                   value="${openTime}" 
                                   ${(isDisabled || range.enabled === false) ? 'disabled' : ''} 
                                   data-index="${index}" 
                                   data-duration-value="${duration.duration_value}"
                                   data-duration-id="${duration.id}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>{{ t('Close At') }}:</label>
                    <div class="d-flex flex-row align-items-center">
                        <div class="input-icon">
                            <i class="material-icons fs-5">schedule</i>
                            <input type="text" class="form-control close-time" 
                                   value="${closeTime || ''}" 
                                   disabled 
                                   data-index="${index}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label>{{ t('location') }}:</label>
                    <div class="d-flex flex-row align-items-center">
                        <div class="input-icon">
                            <input type="text" class="form-control location" value="${location?.address_title || range.location || ''}" disabled data-index="${index}" />
                            <input type="hidden" class="form-control location-id" value="${duration.location_id}" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>{{ t('Title') }}:</label>
                    <div class="d-flex flex-row align-items-center">
                        <div class="input-icon">
                            <input type="text" class="form-control title" value="${duration.duration_title || range.title || ''}" disabled data-index="${index}" />
                            <input type="hidden" class="form-control duration-id" value="${duration.id}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }).join('');
}
    function calculateCloseTime(openTime, durationValue) {
        if (!openTime || !durationValue) return '';
        const [hours, minutes] = openTime.split(':').map(Number);
        const openDate = new Date();
        openDate.setHours(hours, minutes, 0, 0);
        const closeDate = new Date(openDate.getTime() + durationValue * 60000);
        return `${String(closeDate.getHours()).padStart(2, '0')}:${String(closeDate.getMinutes()).padStart(2, '0')}`;
    }

    function initializeTimepickers() {
        $('.open-time').each(function() {
            const $input = $(this);
            const durationValue = $input.data('duration-value');
            
            $input.datetimepicker({
                format: 'HH:mm',
                icons: {
                    time: 'fas fa-clock',
                    up: 'fa fa-angle-up',
                    down: 'fa fa-angle-down'
                },
                minDate: moment().startOf('day'),
                maxDate: moment().endOf('day'),
                stepping: 15,
                useCurrent: false
            }).on('change.datetimepicker', function(e) {
                const openTime = $(this).val();
                const durationValue = $(this).data('duration-value');
                if (openTime && durationValue) {
                    const closeTime = calculateCloseTime(openTime, durationValue);
                    $(this).closest('.time-range').find('.close-time').val(closeTime);
                }
            });
        });
    }

    function applyAccordionBehavior() {
        $('.accordion').off('click').on('click', function () {
            $(this).toggleClass("active").next('.panel').toggle();
            const $icon = $(this).find('i.material-icons');
            $icon.text($(this).hasClass("active") ? 'keyboard_arrow_right' : 'keyboard_arrow_down');
        });

        $(document).on('change', '.toggle-all-time-ranges', function() {
            const panel = $(this).closest('.panel');
            const isEnabled = !$(this).is(':checked');
            const label = $(this).closest('.form-check-label').find('span');
            
            label.text(isEnabled ? '{{ t("Disable All") }}' : '{{ t("Enable All") }}');
            panel.find('.open-time').prop('disabled', !isEnabled);
            panel.find('.enable-row').prop('checked', isEnabled);
            
            if (isEnabled) {
                panel.find('.open-time').prop('disabled', false);
            }
            
            panel.find('.time-range-error').hide();
        });

        $(document).on('change', '.enable-row', function() {
            const $row = $(this).closest('.time-range');
            const isEnabled = $(this).is(':checked');
            const panel = $row.closest('.panel');
            const isGlobalDisabled = panel.find('.toggle-all-time-ranges').is(':checked');
            
            $row.find('.open-time').prop('disabled', isGlobalDisabled || !isEnabled);
        });
    }

    function collectSlotsData() {
        const slotsData = [];
        $('#accordion-container .panel').each(function () {
            const dayKey = $(this).prev('.accordion').data('day-key');
            const day = daysOfWeek[dayKey];
            const isDisabled = $(this).find('.toggle-all-time-ranges').is(':checked');
            const timeRanges = $(this).find('.time-range').map(function () {
                const isRowEnabled = $(this).find('.enable-row').is(':checked');
                const openTime = $(this).find('.open-time').val();
                const durationValue = $(this).find('.open-time').data('duration-value');
                const closeTime = calculateCloseTime(openTime, durationValue);
                
                return {
                    enabled: isRowEnabled,
                    open_time: isRowEnabled ? openTime : '',
                    close_time: isRowEnabled ? closeTime : '',
                    location_id: $(this).find('.location-id').val(),
                    duration_id: $(this).find('.duration-id').val(),
                    title: $(this).find('.title').val()
                };
            }).get().filter(range => range.open_time);

            slotsData.push({ 
                day, 
                day_index: dayKey, 
                disabled: isDisabled, 
                time_ranges: timeRanges 
            });
        });
        return slotsData;
    }

    function saveData(updateParam, data, postId) {
        $.ajax({
            type: "POST",
            url: "{{ route('update-post-ajax') }}",
            data: {
                updateParam,
                paramValue: JSON.stringify(data),
                postId: postId,
                _token: csrfToken
            },
            success: (response) => {
                console.log("{{ t('Time range updated successfully') }}");
                $('#slotDetailsModal').modal('hide');
                showAlert('success', "{{ t('Time availability saved successfully') }}");
            },
            error: (xhr, status, error) => {
                console.error(`{{ t('AJAX Error') }}: ${status} - ${error}`);
                $('#validation-message').html('<div class="alert alert-danger">{{ t('Failed to save time ranges. Please try again.') }}</div>');
            }
        });
    }

    function fetchData(postId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: "{{ route('fetch-post-data') }}",
                type: 'POST',
                data: {
                    postId: postId,
                    _token: csrfToken
                },
                success: function(response) {
                    if (response && response.selectedValue) {
                        postDurations = response.selectedValue.postDurations || [];
                        postDurations.forEach(duration => {
                            $(`.time-range[data-duration-id="${duration.id}"] .open-time`)
                                .data('duration-value', duration.duration_value);
                        });
                        shippingAddresses = response.selectedValue.shipping_addresses || [];
                        
                        resolve(response);
                    } else {
                        reject('No data available');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('An error occurred:', error);
                    reject(error);
                }
            });
        });
    }
    $('#save-slot-btn').on('click', function () {
        const status = $('input[name="slot-status"]:checked').val();
        if (!status) {
            $('#validation-message').html('<div class="alert alert-danger">{{ t('Please select a slot status') }}</div>');
            return;
        }

        if (!postId) {
            $('#validation-message').html('<div class="alert alert-danger">{{ t('Post ID is not set') }}</div>');
            return;
        }

        saveData('time_range', {
            status,
            slots: collectSlotsData()
        }, postId);
    });

    $('.time-availability').on('click', function () {
        postId = $(this).data('post');
        $('#slotDetailsModal').data('postId', postId).modal('show');

        fetchData(postId).then(response => {
            const timeData = response.selectedValue.time_range || {};
            const status = timeData.status; 
            
            if (status === 'open') {
                $('#open').prop('checked', true);
            } else if (status === 'temp_closed') {
                $('#temp_closed').prop('checked', true);
            } else if (status === 'perm_closed') {
                $('#perm_closed').prop('checked', true);
            } else {
                $('#open').prop('checked', true);
            }

            createAccordion(timeData.slots || []);
        }).catch(error => {
            console.error('Failed to fetch data:', error);
            $('#validation-message').html('<div class="alert alert-danger">{{ t('Failed to load time availability data') }}</div>');
        });
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.modal').length && $('#slotDetailsModal').hasClass('show')) {
            $('#slotDetailsModal').modal('hide');
        }
    });

    function showAlert(type, message) {
        const alertHtml = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
        
        $('#alerts-container').append(alertHtml);
        setTimeout(() => $('.alert').alert('close'), 5000);
    }
});
</script>

<style>
.panel-invalid {
    border: 2px solid #dc3545 !important;
    border-radius: 4px;
    padding: 15px;
    margin-top: 5px;
    background-color: rgba(220, 53, 69, 0.05);
}

.accordion-invalid {
    color: #dc3545 !important;
    font-weight: bold;
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.time-range.border-danger {
    background-color: rgba(220, 53, 69, 0.05);
}
</style>


<script>
      $(document).ready(function () {
        var htmlElement = document.documentElement;
        var lang = htmlElement.getAttribute('lang');
        var dir = htmlElement.getAttribute('dir');
        let selectedRecurringType ='none';
        const selectedDays = [];
        const checkboxes = document.querySelectorAll('.weekDayBox');
        const selectedDates = [];
        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth();
        const today = new Date();
        let postId;
        let savedDatafromDb = null;
        let data={};
        updateCalendars();

        function handleFormChanges(savedData = null) {         
    if (savedData && savedData.type && savedData instanceof jQuery.Event) {
        savedData = savedDatafromDb || {};
    } else {
        if (savedData !== undefined) {
            savedDatafromDb = savedData;
        }
    }
    const selectedOption = $('input[name="tabOptions"]:checked').val() || savedData.selectedTabOption;
    const today = formatDate(new Date());
    let startDate = $('#startDate').val() || savedData?.startDate || today;
     data = {
        startDate: startDate,
        selectedTabOption: selectedOption,
        postId: savedData?.postId || postId
    };

    if (selectedOption === 'days') {
        data = {
            ...data,
            repeatOption: $('input[name="repeatOption"]:checked').val() || savedData.repeatOption || 'includeWeekends',
            endRepeatOptionDays: $('input[name="endRepeatOptionsDays"]:checked').val() || savedData.endRepeatOptionDays || 'neverStopDays',
            endRepeatOption: getEndRepeatOption('Days') || savedData.endRepeatOption
        };
        generalRecurring(data);
    } else if (selectedOption === 'week') {
        data = {
            ...data,
            selectedDays: selectedDays || savedData?.selectedDays || [],
            endRepeatOptionWeek: $('input[name="endRepeatOptionsWeek"]:checked').val() || savedData?.endRepeatOptionWeek || 'neverStopWeek',
            endRepeatOption: getEndRepeatOption('Week') || savedData?.endRepeatOption || {}
        };
        weekDaysRecurring(data);  
    } else if (selectedOption === 'month') {
        const CustomSelectedDates = savedData?.selectedDates || [] ;
        data = {
            ...data,
            selectedDates: savedData?.selectedDates || selectedDates, 
            endRepeatOption: getEndRepeatOption('Month') || savedData?.endRepeatOption || {},
            endRepeatOptionMonth: $('input[name="endRepeatOptionsMonth"]:checked').val() || 'neverStopMonth',
        };
        generalRecurring(data);  
         
    }
    updateCalendarDisplay(); 
    return data;
}


$('#startDate, input[name="tabOptions"], input[name="repeatOption"], input[name="endRepeatOptionsDays"], #endDateDays, #occurrencesNumberDays').on('change', handleFormChanges);
$('input[name="endRepeatOptionsWeek"], #endDateWeek, #occurrencesNumberWeek').on('change', handleFormChanges);
$('#endDateMonth ,#occurrencesNumberMonth,#calen ').on('change', function () {
    console.log("changes occue");
    handleFormChanges();
});



    const csrfToken = $('meta[name="csrf-token"]').attr('content');
              function fetchData(postId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: "{{ route('fetch-post-data') }}",
                    type: 'POST',
                    data: {
                        postId: postId,
                        _token: csrfToken
                    },
                    success: function(response) {
                        
                        if (response && response.selectedValue) {
                            resolve(response);
                        } else {
                            reject('No data available');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('An error occurred:', error);
                        reject(error);
                    }
                });
            });
        }


    $('#saveOptions').on('click', function() {
            if (Object.values(data).some(value => Array.isArray(value) ? value.length > 0 : value)) {
               
                            $.ajax({
                                url: '{{ route('update-post-ajax') }}',
                                method: 'POST',
                                data: {
                                    updateParam: 'date_range',  
                                    paramValue: JSON.stringify(data),  
                                    postId: postId
                                },
                                success: function(response) {
                                    $('#errorAlert').hide();
                                    $('#successAlert').show().addClass('show'); 
                                },
                                error: function(xhr, status, error) {
                                    $('#successAlert').hide();

        $('#errorAlert').show().addClass('show');
                                }
                            });
                        } else {
                            alert('No options selected. Please make a selection before saving.');
                        }
            });
    
        $('.openModalBtn').on('click', function () {
            $('#optionModal').modal('show');
            postId = $(this).data('post');
            fetchData(postId).then(response => {

                const dateRangeData = response.selectedValue.dateRangeData || {};
                handleFormChanges(dateRangeData);
                populateForm(dateRangeData);
                
            }).catch(error => {
                console.error('Failed to fetch data:', error);
            });
        });
      
function generalRecurring(dates) {
    if (!dates || !dates.startDate) {
        console.error("No startDate provided in the data.");
        return;
    }

    let currentDate = new Date(dates.startDate);
    const allSelectedDates = new Set();
    let count = 0;
    const CustomSelectedDates = dates.selectedDates;
    const maxCount = dates.endRepeatOption.occurrences || 100;
    const untilEndDate = dates.endRepeatOption.endDate ? new Date(dates.endRepeatOption.endDate) : null;
    const includeWeekends = dates.repeatOption === 'includeWeekends';
    while (count < maxCount && (!untilEndDate || currentDate <= untilEndDate)) {
        const dayOfWeek = currentDate.getDay();
        
        if (!includeWeekends && (dayOfWeek === 0 || dayOfWeek === 6)) {
            currentDate.setDate(currentDate.getDate() + 1);
            continue;
        }
        const dateString = formatDate(currentDate); 
        if (count >= maxCount || (untilEndDate && currentDate > untilEndDate)) {
            break;
        }
        if (!allSelectedDates.has(dateString)) {
                allSelectedDates.add(dateString);
                count++;
            }
        currentDate.setDate(currentDate.getDate() + 1);
    }

    if (allSelectedDates.size > 0) {
        selectedDates.length = 0;
        selectedDates.push(...allSelectedDates);
       updateCalendarDisplay();
    } else {
        console.warn("No valid dates selected.");
    }
    
}

function highlightRandomDates(randomDates) {
    const $container = $('#calendar-1');
    const Dates = Array.isArray(randomDates.selectedDates) ? randomDates.selectedDates : []; 
    $container.find('td').each(function () {
        const $cell = $(this);
        const dateString = $cell.data('date'); 
        if (Dates.includes(dateString)) {
          
            if (!$cell.hasClass('bg-primary') && !$cell.hasClass('text-white')) {
                $cell.css('background-color', '#007bff');
                $cell.css('color', 'white');  
            }
        }
    });

    updateCalendarDisplay(); 
}

function weekDaysRecurring(dates) {
    if (!dates || !dates.startDate) {
        console.error("No startDate provided in the data.");
  crreturn;
    }

    let currentDate = new Date(dates.startDate);
    const allSelectedDates = new Set();
    let count = 0;
    const maxCount = dates.endRepeatOption?.occurrences || 100;
    const untilEndDate = dates.endRepeatOption?.endDate ? new Date(dates.endRepeatOption.endDate) : null;
    const defaultDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
    const daysOfWeekMap = {
        'Mon': 1, 'Tue': 2, 'Wed': 3, 'Thu': 4, 'Fri': 5, 'Sat': 6, 'Sun': 0
    };
    const recurrenceDays = dates.selectedDays && dates.selectedDays.length > 0 
        ? dates.selectedDays.map(day => daysOfWeekMap[day]) 
        : defaultDays.map(day => daysOfWeekMap[day]); 

    while (count < maxCount && (!untilEndDate || currentDate <= untilEndDate)) {
        const currentDayOfWeek = currentDate.getDay();
        if (recurrenceDays.includes(currentDayOfWeek)) {
            const dateString = formatDate(currentDate);
            if (!allSelectedDates.has(dateString)) {
                allSelectedDates.add(dateString);
                count++;
            }
        }
        currentDate.setDate(currentDate.getDate() + 1);
        if (count >= maxCount || (untilEndDate && currentDate > untilEndDate)) {
            break;
        }
    }
    if (allSelectedDates.size > 0) {
        selectedDates.length = 0;
        selectedDates.push(...allSelectedDates);
        updateCalendarDisplay();
    } else {
        console.warn("No valid dates selected.");
    }
}

function formatDate(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
}



function populateForm(dateRangeData) {
    var selectedOption = dateRangeData.selectedTabOption || '';
    var selectedDates = [];
    var selectedDaysDB = dateRangeData.selectedDays;
    function setTabVisibility(option) {
        $('.tab-pane').removeClass('show active');
        $('#' + option).addClass('show active');
    }

    function handleEndRepeatOptions(options, prefix) {
        if (options) {
            if (options.endRepeatOption) {
                $('#' + prefix + 'EndDate').val(options.endRepeatOption.endDate || '');
                $('#' + prefix + 'Occurrences').val(options.endRepeatOption.occurrences || '');
            }

            if (options.endRepeatOptionDays === 'untilDateDays') {
                $('#' + prefix + 'UntilDate').prop('checked', true).trigger('change');
            } else if (options.endRepeatOptionDays === 'untilOccurrencesDays') {
                $('#' + prefix + 'UntilOccurrences').prop('checked', true).trigger('change');
            } else {
                $('#' + prefix + 'Never').prop('checked', true);
            }
        }
    }

                     if (selectedDaysDB) {
                        selectedDaysDB.forEach(day => {
                            selectedDays.push(day);
                        });
                    }
        checkboxes.forEach(checkbox => {
            const day = checkbox.value;
            
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    if (!selectedDays.includes(day)) {
                        selectedDays.push(day);
                        handleFormChanges();
                    }
                } else {
                    const index = selectedDays.indexOf(day);
                    if (index > -1) {
                        selectedDays.splice(index, 1);
                        handleFormChanges();
                    }
                }
               
            });
        });
        
    if (selectedOption === 'days') {
        dailyOptions = dateRangeData || '';
        $('#daysOption').prop('checked', true);
        setTabVisibility('days');
        $('#startDate').val(dailyOptions.startDate);
        if (dailyOptions.repeatOption === 'includeWeekends') {
            $('#repeatDailyIncludeWeekends').prop('checked', true);
        } else if (dailyOptions.repeatOption === 'excludeWeekends') {
            $('#repeatDailyExcludeWeekends').prop('checked', true);
        }

        if (dailyOptions.endRepeatOption) {
            $('#endDateDays').val(dailyOptions.endRepeatOption.endDate || '');
            $('#occurrencesNumberDays').val(dailyOptions.endRepeatOption.occurrences || '');
        }

        if (dailyOptions.endRepeatOptionDays === 'untilDateDays') {
            $('#untilDateDays').prop('checked', true).trigger('change');
        } else if (dailyOptions.endRepeatOptionDays === 'untilOccurrencesDays') {
            $('#untilOccurrencesDays').prop('checked', true).trigger('change');
        } else {
            $('#neverDays').prop('checked', true);
        }
    }

    function handleWeeklyOptions(options) {
        const selectedDays = options.selectedDays || [];
        $('input[type="checkbox"]').each(function() {
            const day = $(this).val();
            $(this).prop('checked', selectedDays.includes(day));
        });
        $('#startDate').val(options.startDate);
        if (options.endRepeatOptionWeek === 'untilDateWeek') {
            $('#endDateWeek').val(options.endRepeatOption.endDate).prop('disabled', false);
            $('#occurrencesNumberWeek').prop('disabled', true);
            $('#untilDateWeek').prop('checked', true).trigger('change');
        } else if (options.endRepeatOptionWeek === 'untilOccurrencesWeek') {
            $('#untilOccurrencesWeek').prop('checked', true).trigger('change');
            $('#occurrencesNumberWeek').val(options.endRepeatOption.occurrences).prop('disabled', false);
            $('#endDateWeek').prop('disabled', true);
        } else if(options.endRepeatOptionWeek === 'neverStopWeek'){
            $('#neverStopWeek').prop('disabled', false).trigger('change');
            $('#endDateWeek').prop('disabled', true);
            $('#occurrencesNumberWeek').prop('disabled', true);
        }
    }

    function handleMonthlyOptions(options) {
        $('#startDate').val(options.startDate);
        if (options.endRepeatOptionMonth === 'untilDateMonth') {
            $('#endDateMonth').val(options.endRepeatOption.endDate).prop('disabled', false).trigger('change');
            $('#occurrencesNumberMonth').prop('disabled', true);
            $('#untilDateMonth').prop('checked', true);
        } else if (options.endRepeatOptionMonth === 'untilOccurrencesMonth') {
            $('#occurrencesNumberMonth').val(options.endRepeatOption.occurrences).prop('disabled', false).trigger('change');
            $('#endDateMonth').prop('disabled', true);
            $('#untilOccurrencesMonth').prop('checked', true);
        } else if (options.endRepeatOptionMonth === 'neverStopMonth') {
            $('#endDateMonth').prop('disabled', true).trigger('change');
            $('#occurrencesNumberMonth').prop('disabled', true);
            $('#neverStopMonth').prop('checked', true);
        }
    }


    switch (selectedOption) {
        case 'days':
            dailyOptions =  dateRangeData || '';
            $('#daysOption').prop('checked', true);
            setTabVisibility('days');
            handleEndRepeatOptions(dailyOptions, 'days');
            updateCalendarDisplay();
            break;
        
        case 'week':
            weeklyOptions =   dateRangeData || '';
            $('#weekOption').prop('checked', true);
            setTabVisibility('week');
            handleWeeklyOptions(weeklyOptions);
            updateCalendarDisplay();
            break;
        
        case 'month':
            monthlyData = dateRangeData || '';
            $('#monthOption').prop('checked', true);
            setTabVisibility('month');
            handleMonthlyOptions(monthlyData); 
            highlightRandomDates(monthlyData);
            updateCalendarDisplay();

            break;
        
        default:
            $('#daysOption').prop('checked', true);
            setTabVisibility('days');
            break;
    }
    
        allowCustomDateSelection(selectedOption);  
        showDatesOnly(selectedOption);
    
}

        
        $('input[name="tabOptions"]').on('change', function () {
            var selectedTab = $(this).val();
            $('.tab-pane').removeClass('show active');
            $('#' + selectedTab).addClass('show active');
             
            allowCustomDateSelection(selectedTab);
            showDatesOnly(selectedTab);
            updateCalendarDisplay(); 
        });

       
        function handleEndRepeatChange(type) {
            $('input[name="endRepeatOptions' + type + '"]').on('change', function () {
                var selectedOption = $(this).val();
                var endDateField = '#endDate' + type;
                var occurrencesField = '#occurrencesNumber' + type;

                if (selectedOption == 'untilDate'+ type ) {
                    $(endDateField).prop('disabled', false);
                    $(occurrencesField).prop('disabled', true);
                } else if (selectedOption == 'untilOccurrences'+ type) {
                    $(endDateField).prop('disabled', true);
                    $(occurrencesField).prop('disabled', false);
                } else if(selectedOption == 'never'+type){
                    $(endDateField).prop('disabled', true);
                    $(occurrencesField).prop('disabled', true);
                }
                
            });
        }
              
        ['Days', 'Week', 'Month'].forEach(function(type) {
            handleEndRepeatChange(type);
        });

        function getEndRepeatOption(type) {
            var endDateField = '#endDate' + type;
            var occurrencesField = '#occurrencesNumber' + type;
            return {
                endDate: $(endDateField).is(':enabled') ? $(endDateField).val() : null,
                occurrences: $(occurrencesField).is(':enabled') ? $(occurrencesField).val() : null
            };
        }

       function generateCalendar(containerId, year, month, displayId) {
    const monthsOfYear = ["{{ t('January') }}", "{{ t('February') }}", "{{ t('March') }}", "{{ t('April') }}", "{{ t('May') }}", "{{ t('June') }}", "{{ t('July') }}", "{{ t('August') }}", "{{ t('September') }}", "{{ t('October') }}", "{{ t('November') }}", "{{ t('December') }}"];

    const $container = $('#' + containerId);
    const $display = $('#' + displayId);
    $container.empty();
    
    const monthName = monthsOfYear[month];
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1).getDay();
    
    $display.text(`${monthName} ${year}`);
    
    let row = '<tr>';
    
    for (let i = 0; i < firstDayOfMonth; i++) {
        row += '<td></td>';
    }
    
    for (let day = 1; day <= daysInMonth; day++) {
        const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const isSelected = selectedDates.includes(dateString);
        const isToday = dateString === getCurrentDateString();
        
        row += `<td class="text-center ${isToday ? 'today' : ''} ${isSelected ? 'bg-primary text-white' : ''}" 
                data-date="${dateString}">${day}</td>`;
        
        if ((firstDayOfMonth + day) % 7 === 0) {
            row += '</tr><tr>';
        }
    }

    const totalCells = firstDayOfMonth + daysInMonth;
    if (totalCells % 7 !== 0) {
        for (let i = totalCells; i % 7 !== 0; i++) {
            row += '<td></td>';
        }
    }

    row += '</tr>';
    $container.html(row);   
    
    updateCalendarDisplay();
}

function allowCustomDateSelection(type) {
    const $container = $('#calendar-1');

    if ($container.find('td').length === 0) {
        console.error("No calendar dates found in the container.");
        return;
    }

    $container.find('td').each(function () {
        const $cell = $(this);
        const dateString = $cell.data('date');

        if (type === 'month') {
            $cell.off('click').on('click', function () {
                toggleDateSelection($(this));
            });
        } else {
            $cell.off('click');
        }
    });
    
}


function toggleDateSelection($cell) {
    const dateString = $cell.data('date');
    const index = selectedDates.indexOf(dateString);

    if (index === -1) {
        selectedDates.push(dateString);
        $cell.addClass('bg-primary text-white');
    } else {
        selectedDates.splice(index, 1);
        $cell.removeClass('bg-primary text-white');
    }
    selectedDates.sort((a, b) => new Date(a) - new Date(b));
    updateCalendarDisplay();
}



function getCurrentDateString() {
    const today = new Date();
    return `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;
}

function updateCalendarDisplay() {
    const $container = $('#calendar-1');

    $container.find('td').each(function () {
        const $cell = $(this);
        const dateString = $cell.data('date');

        if (selectedDates.includes(dateString)) {
            $cell.addClass('bg-primary text-white');
        } else {
            $cell.removeClass('bg-primary text-white');
        }
    });
}


function changeMonth(offset) {
    currentMonth += offset;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    updateCalendars();
}

$('#prev-month').on('click', function () {
    changeMonth(-1);
});

$('#next-month').on('click', function () {
    changeMonth(1);
});
function showDatesOnly(type) {
    if(type === 'month'){
        $('#calendarYear span').hide();
        $('#calendarDays').hide();
        $('#calendarYear').css('background-color', 'white');


    }else{
        $('#calendarDays').show();
        $('#calendarYear span').show();
        $('#calendarYear').css({
        'background-color': '#f2f2f2',
        'padding': '0'
    });
    }
    updateCalendarDisplay();
}


function updateCalendars() {
    generateCalendar('calendar-1', currentYear, currentMonth, 'month-year-display-1');
}

updateCalendars();
        $('#closeBtn,  #cancelBtn').click(function () {
            
            $('#successAlert').hide();
        });
    });
</script>

@endsection
