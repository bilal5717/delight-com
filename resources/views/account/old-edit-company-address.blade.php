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
                <div class="col-md-3 page-sidebar">
                    @includeFirst([
                        config('larapen.core.customizedViewPath') . 'account.inc.sidebar',
                        'account.inc.sidebar',
                    ])
                </div>
                <!--/.page-sidebar-->

                <div class="col-md-9 page-content">

                    @include('flash::message')

                    @if (isset($errors) and $errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
                            <ul class="list list-check">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
                    <div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>

                    <div class="inner-box default-inner-box">

                        <!--  address details -->
                        <div class="card card-default mt-3">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a href="{{ route('company_address') }}">{{ trans('company.company_address') }}</a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'addressPanel') ? 'show' : '' }}"
                                id="addressPanel">
                                <div class="card-body">
                                    <form name="details" class="form-horizontal" role="form" method="POST"
                                        action="{{ route('update-company-address', ['id' => $company_address->id]) }}"
                                        enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input name="_method" type="hidden" value="POST">

                                        <!-- Address -->
                                        <div class="form-group row ">
                                            <label class="col-md-3 col-form-label text-right" for="address">
                                                {{ trans('company.address') }} <sup class="sup">*</sup>
                                            </label>
                                            <div class="col-md-9">
                                                <input name="address" type="text" id="address"
                                                    placeholder="Enter address" value="{{ $company_address->address }}"
                                                    class="form-control @error('address') is-invalid @enderror" autofocus>
                                                @error('address')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- city_id -->
                                        <div id="cityBox" class="form-group row">
                                            <label class="col-md-3 col-form-label" for="city_id">{{ t('city') }}
                                                <sup>*</sup></label>
                                            <div class="col-md-9">
                                                <select id="city" name="city_id" class="form-control sselecter"
                                                    required>
                                                    <option value="{{ $company_address->city_id }}">
                                                        {{ t('select_a_city') }}
                                                    </option>
                                                </select>
                                                @error('city_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- state -->
                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-right">{{ trans('company.company_state') }}
                                                <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <input name="state" type="text" id="state"
                                                    placeholder="Enter state name" value="{{ $company_address->address }}"
                                                    class="form-control @error('state') is-invalid @enderror">
                                                @error('state')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- pincode -->
                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-right">{{ trans('company.company_pincode') }}
                                                <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <input name="pincode" type="text" id="pincode"
                                                    placeholder="Enter Pincode" value="{{ $company_address->pincode }}"
                                                    class="form-control @error('pincode') is-invalid @enderror">
                                                @error('pincode')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- country -->
                                        <div class="form-group row ">
                                            <label
                                                class="col-md-3 col-form-label text-right">{{ trans('company.company_country') }}
                                                <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <select name="country" id="country" class="form-control sselecter @error('country') is-invalid @enderror">
                                                    @foreach ($countries as $ctry)
                                                        <option data-code="{{ $ctry->code }}" {{$ctry->code === $company_address->country ? 'selected' : ''}} value="{{ $ctry->code }}">
                                                            {{ $ctry->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('country')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="default_address" value="1"
                                                        {{ $company_address->default_address ? ' checked' : '' }}>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        {{ trans('company.Set as default address') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Button -->
                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9">
                                                <button type="submit"
                                                    class="btn btn-primary">{{ trans('company.submit') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/.page-content-->
            </div>
            <!--/.row-->
        </div>
        <!--/.container-->
    </div>
    <!-- /.main-container -->
@endsection

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    <style>
        .sup {
            color: red;
        }

        body {
            font-family: "Open Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/fileinput/locales/' . config('app.locale') . '.js') }}" type="text/javascript"></script>
    <script>
        // var cityId = '{{ old('city_id', $company_address->city_id) }}';
        var city = '{{ old('city_id', $company_address->city_id) }}';
        var country = '{{ old('country', $company_address->country) }}';
    </script>

    <script src="{{ url('assets/js/app/d.country.select.city.js') . vTime() }}"></script>
@endsection
@section('after_styles')
@endsection

@section('after_scripts')
@endsection

@includeFirst([
    config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.form-assets',
    'post.createOrEdit.inc.form-assets',
])
