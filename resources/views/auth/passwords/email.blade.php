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

@section('after_styles')
	@include('common.structure-inc', ["pageTitle" => "password", "pageUrl" => "password/reset"])
    <link rel="stylesheet" href="{{ asset('css/vibrant-password.css') }}">
@endsection

@section('content')
	@if (!(isset($paddingTopExists) and $paddingTopExists))
		<div class="h-spacer"></div>
	@endif
	<div class="main-container vibrant-container">
		<div class="container">
			<div class="row">

				@if (isset($errors) and $errors->any())
					<div class="col-xl-12">
						<div class="alert alert-danger vibrant-alert">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				@if (session('status'))
					<div class="col-xl-12">
						<div class="alert alert-success vibrant-alert vibrant-alert-success">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('status') }}</p>
						</div>
					</div>
				@endif

				@if (session('email'))
					<div class="col-xl-12">
						<div class="alert alert-danger vibrant-alert">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('email') }}</p>
						</div>
					</div>
				@endif
					
				@if (session('phone'))
					<div class="col-xl-12">
						<div class="alert alert-danger vibrant-alert">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('phone') }}</p>
						</div>
					</div>
				@endif
					
				@if (session('login'))
					<div class="col-xl-12">
						<div class="alert alert-danger vibrant-alert">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<p>{{ session('login') }}</p>
						</div>
					</div>
				@endif

				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 login-box vibrant-box">
					<div class="card card-default">
						<div class="panel-intro text-center">
							<h2 class="logo-title vibrant-title">
								{{ t('password') }} <span class="vibrant-title-accent">{{ t('reset') }}</span>
							</h2>
						</div>
						
						<div class="card-body">
							<form id="pwdForm" role="form" method="POST" action="{{ url('password/email') }}">
								{!! csrf_field() !!}
								
								<!-- login -->
								<?php $loginError = (isset($errors) and $errors->has('login')) ? ' is-invalid' : ''; ?>
								<div class="form-group vibrant-form-group">
									<label for="login" class="col-form-label vibrant-label">{{ getLoginLabel() }}</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text vibrant-input-group-text"><i class="icon-user fa"></i></span>
										</div>
										<input id="login"
											   name="login"
											   type="text"
											   placeholder="{{ getLoginLabel() }}"
											   class="form-control{{ $loginError }} vibrant-input"
											   value="{{ old('login') }}"
										>
									</div>
								</div>
								
								@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.tools.recaptcha', 'layouts.inc.tools.recaptcha'], ['noLabel' => true])
								
								<!-- Submit -->
								<div class="form-group">
									<button id="pwdBtn" type="submit" class="btn btn-primary btn-lg btn-block vibrant-btn vibrant-submit-btn">{{ t('submit') }}</button>
								</div>
							</form>
						</div>
						
						<div class="card-footer text-center vibrant-card-footer">
							<a href="{{ \App\Helpers\UrlGen::login() }}" class="vibrant-link"> {{ t('back_to_the_log_in_page') }} </a>
						</div>
					</div>
					<div class="login-box-btm text-center vibrant-login-box-btm">
						<p class="vibrant-signup-text">
							{{ t('do_not_have_an_account') }} <br>
							<a href="{{ \App\Helpers\UrlGen::register() }}" class="vibrant-signup-link"><strong>{{ t('sign_up_') }}</strong></a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$("#pwdBtn").click(function () {
				$("#pwdForm").submit();
				return false;
			});
		});
	</script>
@endsection