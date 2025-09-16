{{--
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
--}}
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
					@includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar', 'account.inc.sidebar'])
				</div>
				<!--/.page-sidebar-->
				<form id="working-hours" role="form" method="POST" action="{{ url()->current() }}">
					{!! csrf_field() !!}
					<input name="_method" type="hidden" value="POST">

					<input name="working-hours-input" id="working-hours-input" type="hidden" value="">
					<div class="page-content">
						<div class="inner-box">
							<h2 class="title-2"> {{ t('working_hour_setting_page') }} </h2>

							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="working-hour-check" name="working-hour-check" value="active" {{ $working_hours_active === 'active' ? "checked" : "" }}>
								<label class="form-check-label" style="font-size: 15px; margin-left: 20px;">{{ t('enable_working_hour') }}</label>
							</div>
							<hr>
							<p>{{ t('24_hour_clock') }}</p>
							<div style="padding: 10px;" class="row" id="working-hours-div" data-name="form-name"></div>

							<p>{{ t('configure_your_time_zone') }}</p>

						</div>
						<!--/.inner-box-->
					</div>

					<!-- Button -->
					<div class="form-group row mt-1">
						<div class="col-md-12">
							<a id="updateBtn" class="btn btn-primary float-right">{{ t('Update') }}</a>
						</div>
					</div>
				</form>
				<!--/.page-content-->

			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->
@endsection

@section('after_scripts')
<style>
	.input-group-addon,
	.input-group-btn {
		white-space: nowrap;
		vertical-align: middle;
	}

	.input-group-addon {
		padding: 12px 12px;
		font-size: 12px;
		font-weight: normal;
		line-height: 1;
		color: #555555;
		text-align: center;
		background-color: #eeeeee;
		border: 1px solid #cccccc;
		border-radius: 4px;
	}
</style>
<script>
	var translations = {
		Sunday: "{{ t('Sunday') }}",
		Monday: "{{ t('Monday') }}",
		Tuesday: "{{ t('Tuesday') }}",
		Wednesday: "{{ t('Wednesday') }}",
		Thursday: "{{ t('Thursday') }}",
		Friday: "{{ t('Friday') }}",
		Saturday: "{{ t('Saturday') }}"
	};
</script>
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/font-awesome4.0.3.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/jquery.businessHours.css') }}"/>
<script src="{{ asset('/assets/js/jquery.timepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/jquery.timepicker.min.css') }}"/>
<script src="{{ asset('/assets/js/jquery.businessHours.js') }}"></script>

	<script>
	var working_hours = $("#working-hours-div");
	let saved_state = '{!! $working_hours !!}';
	if(saved_state == ''){
		saved_state = JSON.stringify([
			{"isActive":true,"timeFrom":"9:00","timeTill":"17:00"},
			{"isActive":true,"timeFrom":"9:00","timeTill":"17:00"},
			{"isActive":true,"timeFrom":"9:00","timeTill":"17:00"},
			{"isActive":true,"timeFrom":"9:00","timeTill":"17:00"},
			{"isActive":true,"timeFrom":"9:00","timeTill":"17:00"},
			{"isActive":false,"timeFrom":null,"timeTill":null},
			{"isActive":false,"timeFrom":null,"timeTill":null}
		]);
	}

	var businessHoursManager = working_hours.businessHours({
		operationTime: JSON.parse(saved_state),
		postInit:function(){
			working_hours.find('.operationTimeFrom, .operationTimeTill').timepicker({
				'timeFormat': 'H:i',
				'step': 15,
			});
		},
		dayTmpl: '<div class="dayContainer" style="width: 80px;">' +
				'<div data-original-title="" class="colorBox"><input type="checkbox" class="invisible operationState"/></div>' +
				'<div class="weekday"></div>' +
				'<div class="operationDayTimeContainer">' +
				'<div class="operationTime input-group"><span class="input-group-addon"><i class="fa fa-sun-o"></i></span><input type="text" name="startTime" class="mini-time form-control operationTimeFrom" value=""/></div>' +
				'<div class="operationTime input-group"><span class="input-group-addon"><i class="fa fa-moon-o"></i></span><input type="text" name="endTime" class="mini-time form-control operationTimeTill" value=""/></div>' +
				'</div></div>'
	});

	$("#updateBtn").click(function() {
		$("#working-hours-input").val(JSON.stringify(businessHoursManager.serialize()));
		$("#working-hours").submit();
	});

</script>
@endsection