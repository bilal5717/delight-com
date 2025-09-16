@extends('admin::layouts.master')

@section('header')
    <div class="row page-titles">
        <div class="col-md-7 col-12 align-self-center d-none d-md-block">
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ admin_url() }}">{{ trans('admin.dashboard') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ admin_url('users') }}"
                        class="text-capitalize">{{ trans('admin.users') }}</a></li>
                <li class="breadcrumb-item active">{{ trans('company-admin.view-company') }}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 page-content">

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

                    <div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
                    <div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>

                    <ul class="nav nav-tabs " id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#businessPanel" role="tab"
                                aria-controls="home"
                                aria-selected="true">{{ trans('company-admin.View Company Details') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#paymentPanel" role="tab"
                                aria-controls="profile"
                                aria-selected="false">{{ trans('company-admin.Company Payment Details') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#addressPanel" role="tab"
                                aria-controls="contact"
                                aria-selected="false">{{ trans('company-admin.Company Addresses') }}</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="businessPanel" role="tabpanel"
                            aria-labelledby="home-tab">
                            <div class="card card-default">
                                <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'businessPanel') ? 'show' : '' }}"
                                    id="businessPanel">
                                    <div class="card-body">
                                        <form name="details" class="form-horizontal" role="form" method="POST"
                                            enctype="multipart/form-data">
                                            {!! csrf_field() !!}
                                            <input name="_method" type="hidden" value="POST">
                                            {{-- <input name="panel" type="hidden" value="businessPanel"> --}}

                                            <div class="form-group row">
                                                <label for="logo"
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.company_logo') }}
                                                </label>
                                                <div class="col-9">
                                                    <div class="photo-field">
                                                        <img class="file" src="{{ asset('storage/' . $company->logo) }}"
                                                            width="350px" alt="company logo" />
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- name -->
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.company_name') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span class="form-control">{{ $company->name }}</span>
                                                </div>
                                            </div>

                                            <!-- About Business -->
                                            <div class="form-group row ">
                                                <label class="col-md-3 col-form-label text-right" for="description">
                                                    {{ trans('company.about_business') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span class="form-control" style="height: auto">{!! $company->description !!}</span>
                                                </div>
                                            </div>

                                            <!-- company email -->
                                            <div class="form-group row ">
                                                <label class="col-md-3 col-form-label text-right" for="email">
                                                    {{ trans('company.company_email') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span class="form-control" style="height: auto">{{ $company->email }}</span>
                                                </div>
                                            </div>

                                            <!-- social media -->
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.facebook') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span
                                                        class="form-control">{{ $company->facebook ? $company->facebook : '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.twitter') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span
                                                        class="form-control">{{ $company->twitter ? $company->twitter : '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.instagram') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span
                                                        class="form-control">{{ $company->instagram ? $company->instagram : '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.linkedIn') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span
                                                        class="form-control">{{ $company->linkedIn ? $company->linkedIn : '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.kvk') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span
                                                        class="form-control">{{ $company->kvk ? $company->kvk : '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.wechat') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span
                                                        class="form-control">{{ $company->wechat ? $company->wechat : '-' }}</span>
                                                </div>
                                            </div>

                                            <!-- phone -->
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.phone') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span class="form-control">{{ $company->phone }}</span>
                                                </div>
                                            </div>

                                            <!-- Website -->
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.website') }}</label>
                                                <div class="col-md-9">
                                                    <span
                                                        class="form-control">{{ $company->website ? $company->website : '-' }}</span>
                                                </div>
                                            </div>

                                            <!-- Business Category -->
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.business_category') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span class="form-control">{{ $company->category->name }}</span>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Company size - Revenue -->
                                            <div class="form-group row ">
                                                <label
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.company_size') }}
                                                </label>
                                                <div class="col-md-9">
                                                    <span class="form-control">{!! getRevenue($company->revenue) !!}</span>
                                                </div>
                                            </div>

                                            <!-- Company Registration number  -->
                                            <div class="form-group row ">
                                                <label for="registration_number"
                                                    class="col-md-3 col-form-label text-right">{{ trans('company.registration_number') }}

                                                </label>
                                                <div class="col-md-9">
                                                    <span class="form-control">{{ $company->registration_number }}</span>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="offset-md-3 col-md-9"></div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paymentPanel" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'paymentPanel') ? 'show' : '' }}"
                                id="paymentPanel">
                                <div class="row">
                                    @php
                                        $currencies = config('CompanyPaymentFields');
                                    @endphp
                                    
                                    @foreach ($company->companyPayment as $payment)
                                        <div class="col-md-6 mt-2">
                                            <div class="card border border-light pb-3 pt-2">
                                                @php
                                                    $fields = $currencies[$payment->currency_code];
                                                @endphp
                                                @if ($payment->default_payment == 1)
                                                    <div class="form-group row  mb-0" style="margin-left: 25px">
                                                        <label class="col-form-label text-right pb-0">
                                                            {{ trans('company-admin.default_payment') }}
                                                        </label>&nbsp;&nbsp;
                                                        <b style="margin-top:6px;"> :- </b>
                                                        <div style="margin-left: 4px;margin-top:7px;font-weight:bold">
                                                            {{ $payment['default_payment'] == 1 ? 'Default Selected Payment' : '' }}
                                                        </div>
                                                    </div>
                                                @endif
                                                @foreach ($fields as $field)
                                                    <div class="form-group row  mb-0" style="margin-left: 25px">
                                                        <label
                                                            class="col-form-label text-right pb-0">{{ $field['label'] }}
                                                        </label>&nbsp;&nbsp;
                                                        <b style="margin-top:6px;"> :- </b>
                                                        <div style="margin-left: 4px;margin-top:7px;">
                                                            @if ($field['input_type'] == 'text')
                                                                {{ $payment ? $payment[$field['field_name']] : '' }}
                                                            @elseif ($field['input_type'] == 'radio')
                                                                {{-- {{ $payment[$field['field_name']] }} --}}
                                                                {{ $payment[$field['field_name']] == 0 ? 'Personal' : 'Business' }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="addressPanel" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'addressPanel') ? 'show' : '' }}"
                                id="addressPanel">
                                <div class="row">
                                    {!! csrf_field() !!}
                                    <!-- Address -->
                                    @foreach ($company->companyAddresss as $company_address)
                                        <div class="col-md-6 mt-2">
                                            <div class="card border border-light pb-3 pt-2">
                                                @if ($company_address->default_address == 1)
                                                    <div class="form-group row  mb-0" style="margin-left: 25px">
                                                        <label class="col-form-label text-right pb-0">
                                                            {{ trans('company-admin.default_address') }}
                                                        </label>&nbsp;&nbsp;
                                                        <b style="margin-top:6px;"> :- </b>
                                                        <div style="margin-left: 4px;margin-top:7px;font-weight:bold">
                                                            {{ $company_address->default_address == 1 ? 'Default Selected Address' : '' }}
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="form-group row  mb-0" style="margin-left: 25px">
                                                    <label class="col-form-label text-right pb-0">
                                                        {{ trans('company-admin.address') }}
                                                    </label>&nbsp;&nbsp;
                                                    <b style="margin-top:6px;"> :- </b>
                                                    <div style="margin-left: 4px;margin-top:7px;">
                                                        {{ $company_address->address }}
                                                    </div>
                                                </div>
                                                <div class="form-group row  mb-0" style="margin-left: 25px">
                                                    <label class="col-form-label text-right pb-0">
                                                        {{ trans('company-admin.company_city') }}
                                                    </label>&nbsp;&nbsp;
                                                    <b style="margin-top:6px;"> :- </b>
                                                    <div style="margin-left: 4px;margin-top:7px;">
                                                        {{ $company_address->city->name }}
                                                    </div>
                                                </div>
                                                <div class="form-group row  mb-0" style="margin-left: 25px">
                                                    <label class="col-form-label text-right pb-0">
                                                        {{ trans('company-admin.company_state') }}
                                                    </label>&nbsp;&nbsp;
                                                    <b style="margin-top:6px;"> :- </b>
                                                    <div style="margin-left: 4px;margin-top:7px;">
                                                        {{ $company_address->state }}
                                                    </div>
                                                </div>
                                                <div class="form-group row  mb-0" style="margin-left: 25px">
                                                    <label class="col-form-label text-right pb-0">
                                                        {{ trans('company-admin.company_pincode') }}
                                                    </label>&nbsp;&nbsp;
                                                    <b style="margin-top:6px;"> :- </b>
                                                    <div style="margin-left: 4px;margin-top:7px;">
                                                        {{ $company_address->pincode }}
                                                    </div>
                                                </div>
                                                <div class="form-group row  mb-0" style="margin-left: 25px">
                                                    <label class="col-form-label text-right pb-0">
                                                        {{ trans('company-admin.company_country') }}
                                                    </label>&nbsp;&nbsp;
                                                    <b style="margin-top:6px;"> :- </b>
                                                    <div style="margin-left: 4px;margin-top:7px;">
                                                        {{ $company_address->country }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_styles')
    <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
    @endif
    <style>
        .krajee-default.file-preview-frame:hover:not(.file-preview-error) {
            box-shadow: 0 0 5px 0 #666666;
        }

        .file-loading:before {
            content: " {{ t('Loading') }}...";
        }

        .sup {
            color: red;
        }
        .nav-tabs  .nav-item.show .nav-link, .nav-tabs .nav-link.active{
           font-weight: bold;
        }
    </style>
    <style>
        .error {
            color: red;
            font-weight: 400;
            display: block;
            padding: 6px 0;
            font-size: 14px;
        }

        /* Avatar Upload */
        .photo-field {
            display: inline-block;
            vertical-align: middle;
        }

        .photo-field .krajee-default.file-preview-frame,
        .photo-field .krajee-default.file-preview-frame:hover {
            margin: 0;
            padding: 0;
            border: none;
            box-shadow: none;
            text-align: center;
        }

        .file-input {
            display: table-cell;
            width: 550px;
        }

        .photo-field .krajee-default.file-preview-frame .kv-file-content {
            width: auto;
            height: auto;
        }

        .kv-reqd {
            color: red;
            font-family: monospace;
            font-weight: normal;
        }

        .file-preview {
            padding: 2px;
        }

        .file-drop-zone {
            margin: 2px;
        }

        .file-drop-zone .file-preview-thumbnails {
            cursor: pointer;
        }

        .krajee-default.file-preview-frame .file-thumbnail-footer {
            height: 30px;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
@endsection
