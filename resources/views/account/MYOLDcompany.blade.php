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
                                                href="{{ url('https://www.google.com/maps/place/' . $company->defaultCompanyAddresss->address) }}">
                                                {{ $company->defaultCompanyAddresss->address }}
                                            </a>
                                        </strong>
                                    </p>

                                    <p class="mb-1">
                                        <strong>
                                            {{ t('company_city') }} :&nbsp;
                                        </strong>
                                        <strong>
                                            <span class="text-primary">
                                                {{ $company->defaultCompanyAddresss->city->name }}
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
                                                {{ $company->defaultCompanyAddresss->country }}
                                            </span>
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
                                        <ul
                                            class="mt-3 mb-3 list-unstyled list-inline footer-nav social-list-footer social-list-color footer-nav-inline">
                                            @if ($company->facebook)
                                                <li>
                                                    <a class="icon-color fb" title="" data-placement="top"
                                                        data-toggle="tooltip" href="{{ $company->facebook }}"
                                                        data-original-title="Facebook">
                                                        <i class="fab fa-facebook"></i>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($company->twitter)
                                                <li>
                                                    <a class="icon-color tw" title="" data-placement="top"
                                                        data-toggle="tooltip" href="{{ $company->twitter }}"
                                                        data-original-title="Twitter">
                                                        <i class="fab fa-twitter"></i>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($company->instagram)
                                                <li>
                                                    <a class="icon-color pin" title="" data-placement="top"
                                                        data-toggle="tooltip" href="{{ $company->instagram }}"
                                                        data-original-title="Instagram">
                                                        <i class="icon-instagram-filled"></i>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($company->wechat)
                                                <li>
                                                    <a class="icon-color wechat" title="" data-placement="top"
                                                        data-toggle="tooltip" href="{{ $company->wechat }}"
                                                        data-original-title="wechat">
                                                        <img src="{{ asset('images/wechat.png') }}" width="20" />
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($company->linkedin)
                                                <li>
                                                    <a class="icon-color linkedin" title="" data-placement="top"
                                                        data-toggle="tooltip" href="{{ $company->linkedin }}"
                                                        data-original-title="linkedin">
                                                        <img src="{{ asset('images/linkedin.png') }}" width="20" />
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($company->kvk)
                                                <li>
                                                    <a class="icon-color vk" title="" data-placement="top"
                                                        data-toggle="tooltip" href="{{ $company->kvk }}"
                                                        data-original-title="vk">
                                                        <img src="{{ asset('images/vk.png') }}" width="20" />
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9 page-content col-thin-right mb-4">
                    <div class="inner inner-box items-details-wrapper pb-0">
                        <div class="items-details">
                            <div class="tab-content p-3 mb-3" id="itemsDetailsTabsContent">
                                <div class="tab-pane show active" id="item-details" role="tabpanel"
                                    aria-labelledby="item-details-tab">
                                    <div class="border border-light rounded p-2 mb-3">
                                        <div class="d-flex">
                                            <a href="{{ \App\Helpers\UrlGen::user($company->user) }}">
                                                <img class="me-2 rounded" src="{{ asset('storage/' . $company->logo) }}"
                                                    alt="Generic placeholder image" height="35px">
                                            </a>
                                            <div>
                                                <a href="{{ \App\Helpers\UrlGen::user($company->user) }}" class="text-primary">
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
                                                        {!! strip_tags($company->description) !!} &nbsp;
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
                                        </div>
                                    </div>
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
    <style>
        .vk { background: #2787F5; }
        .linkedin { background: #0077B5; }
        .wechat { background: #2DC100; }
    </style>
@endsection

@section('after_scripts')

@endsection
