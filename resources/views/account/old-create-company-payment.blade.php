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
                        <!--  payment details -->
                        <div class="card card-default mt-3">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <a href="#paymentPanel" data-toggle="collapse"
                                        data-parent="#accordion">{{ trans('company.company_payment_details') }}</a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'paymentPanel') ? 'show' : '' }}"
                                id="paymentPanel">
                                <div class="card-body">
                                    <form name="details" class="form-horizontal" role="form" method="POST"
                                        action="{{ route('save_company_payment') }}" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <input name="_method" type="hidden" value="POST">
                                        
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label">{{ trans('company.select_currency') }}
                                                <sup class="sup">*</sup></label>
                                            <div class="col-md-9">
                                                <select id="currency" name="currency" class="form-control" required>
                                                    <option value=""></option>
                                                    @foreach ($currencies as $field)
                                                        <option data-id="{{ $field->id }}" value="{{ $field->code }}">
                                                            {{ $field->code }} -
                                                            {{ $field->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        
                                        <div class="result form-group row">
                                        </div>

                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9"></div>
                                        </div>
                                        <!-- Button -->
                                        <div class="form-group row">
                                            <div class="offset-md-3 col-md-9">
                                                <button id="submit-btn" disabled type="submit"
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
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/fileinput/locales/' . config('app.locale') . '.js') }}" type="text/javascript"></script>
    <script>
        $("#currency").change(function() {
            let state = this.value;
            var val = $('#currency option:selected').val();
            var id = $('#currency option:selected').data("id");
            $("#form").show();

            $.ajax({
                method: 'GET',
                url: '{{ url('account/company-payment/') }}/' + val,
                success: function(resp) {
                    console.log(resp);
                    $('.result').html(resp.html);
                    if(resp.is_data == true){
                        $("#submit-btn").prop("disabled", false);
                    }else{
                        $("#submit-btn").prop("disabled", true);
                    }
                    
                }
            })
            console.log(JSON.stringify(val));
        });
    </script>
@endsection
