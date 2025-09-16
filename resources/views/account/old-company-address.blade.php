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
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            {{ Session::get('success') }}
                        </div>
                    @endif
                    <div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
                    <div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>

                    <!--  address details -->
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title">
                                <div class="row">
                                    <div class="col-9 mt-2">
                                        <a href="#addressPanel" class="d-inline">{{ trans('company.company_address') }}</a>
                                    </div>
                                    <div class="col-3 float-right">
                                        <a class="btn-add-listing  m-1 p-1"
                                            href="{{ route('create_company_address') }}">{{ trans('company.add_new_address') }}
                                        </a>
                                    </div>
                                </div>
                            </h4>
                        </div>
                        <div class="panel-collapse collapse {{ (old('panel') == '' or old('panel') == 'addressPanel') ? 'show' : '' }}"
                            id="addressPanel">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ trans('company.address') }}</th>
                                                    <th scope="col">{{ trans('company.company_city') }}</th>
                                                    <th scope="col">{{ trans('company.company_country') }}</th>
                                                    <th scope="col">{{ trans('company.default_address') }}</th>
                                                    <th scope="col">{{ trans('company.action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($addresses as $address)
                                                    <tr>
                                                        <td>
                                                            <span data-toggle="tooltip" data-bs-placement="top"
                                                                title="{{ $address->address }}">
                                                                {{ Str::limit($address->address, 50) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $address->city->name }}</td>
                                                        <td>{{ $address->country }}</td>
                                                        <td class="text-center">
                                                            <label class="switch">
                                                                <input type="checkbox"
                                                                    class="switchboxes checkbox-{{ $address->id }} @error('default_address') is-invalid @enderror"
                                                                    name="default_address" value="{{ $address->id }}"
                                                                    {{ $address->default_address ? 'checked' : 1 }}>
                                                                <span class="slider round"></span>
                                                                @error('default_address')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </label>
                                                        </td>
                                                        <td><a class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                                data-bs-placement="bottom" title="Edit"
                                                                href="{{ route('edit_company_address', ['id' => $address->id]) }}">
                                                                <i class="far fa-edit fa-lg"></i>
                                                            </a>
                                                            <a class="delete-address btn btn-sm btn-danger"
                                                                data-toggle="tooltip" data-bs-placement="bottom"
                                                                title="Delete" data-id="checkbox-{{ $address->id }}"
                                                                href="{{ route('delete_company_address', ['id' => $address->id]) }}"><i
                                                                    class="fas fa-trash fa-lg"> </i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                            </tbody>
                                            @endforeach
                                        </table>
                                    </div>
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

        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            height: 16px;
            width: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 2px;
            top: 2px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(13px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/fileinput/locales/' . config('app.locale') . '.js') }}" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $(".switchboxes").change(function() {
                if (!$(this).prop('checked')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'There should be only one default address!',
                    });
                    $(this).prop('checked', true);
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('update-default-address') }}",
                    data: {
                        default_address: $(this).prop('checked') ? $(this).val() : 0,
                        address_id: $(this).val();
                    },
                    success: function() {
                        window.location.reload();
                    }
                })
            });
        });
    </script>
    <script>
        $('.delete-address').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var id = $(this).data('id');
            if ($('.' + id).prop('checked')) {
                Swal.fire({
                    icon: 'warning',
                    title: "{{ trans('company.Oops...') }}",
                    text: "{{ trans('company.You can not delete default selected address!') }}",
                });
                return false;
            }

            Swal.fire({
                title: "{{ trans('company.Are you sure you want to delete this address?') }}",
                text: "{{ trans('company.You wont be able to revert this !') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ trans('company.Yes, delete it!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        success: function() {
                            window.location.reload();
                        }
                    })
                }
            })
        });
    </script>
@endsection
@section('after_styles')
@endsection

@section('after_scripts')
@endsection

@includeFirst([
    config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.form-assets',
    'post.createOrEdit.inc.form-assets',
])
