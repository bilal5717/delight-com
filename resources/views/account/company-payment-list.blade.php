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


                    <!-- Payment details -->
                    <div class="card card-default">
                        <div class="card-header">
                            <h4 class="card-title">
                                <div class="row">
                                    <div class="col-9 mt-2">
                                        <a href="#paymentPanel">{{ t('company_payment_list') }}</a>
                                    </div>
                                    <div class="col-3 float-right">
                                        <a class="btn-add-listing m-1 p-1"
                                            href="{{ route('create_company_payment') }}">{{ t('add_payment_detail') }}
                                        </a>
                                    </div>
                                </div>
                            </h4>
                        </div>

                        <div class="row p-4">
                            <div class="col-md-12">
                                <p>{{ t("You will receive your payments on your default payment details") }}</p>
                            </div>
                            @php
                                $currencies = config('CompanyPaymentFields');
                            @endphp
                            @foreach ($payment_details as $payment)
                                <div class="col-md-6 mb-4">
                                    <div class="card pt-2">
                                        @php
                                            $fields = $currencies[$payment->currency_code] ?? [];
                                        @endphp
                                        <div class="form-group row mb-0" style="margin-left: 25px">
                                            <label class="col-form-label text-right pb-0">{{ t('information') }}
                                            </label>&nbsp;&nbsp;
                                            <b style="margin-top:6px;"> :- </b>
                                            <div style="margin-left: 4px;margin-top:7px;">
                                                {{ $payment['information'] }}
                                            </div>
                                        </div>
                                        @foreach ($fields as $field)
                                           <div class="form-group row mb-0" style="margin-left: 25px">
                                                <label class="col-form-label text-right pb-0">{{ t($field['label']) }}
                                                </label>&nbsp;&nbsp;
                                                <b style="margin-top:6px;"> :- </b>
                                                <div style="margin-left: 4px;margin-top:7px;">
                                                    @if ($field['input_type'] == 'text')
                                                        {{ $payment ? $payment[$field['field_name']] : '' }}
                                                    @elseif ($field['input_type'] == 'radio')
                                                        {{ $payment[$field['field_name']] == 0 ? t('Personal') : t('Business') }}
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="row mx-4 my-2">
                                            <b>{{ t('default_payment') }} : &nbsp;</b>
                                            <label class="switch mt-1" style="height: auto">
                                                <input type="checkbox"
                                                    class="switchboxes checkbox-{{ $payment->id }} @error('default_payment') is-invalid @enderror"
                                                    name="default_payment" value="{{ $payment->id }}"
                                                    {{ $payment->default_payment ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                                @error('default_payment')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </label>
                                            <a class="payment-button btn btn-sm btn-primary mr-2" data-toggle="tooltip"
                                                data-bs-placement="bottom" title="Edit"
                                                href="{{ route('edit_company_payment', ['id' => $payment->id]) }}">
                                                <i class="far fa-edit fa-lg"></i>
                                            </a>
                                            <a class="payment-button delete-payment btn btn-sm btn-danger"
                                                data-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                data-id="checkbox-{{ $payment->id }}"
                                                href="{{ route('delete_company_payment', ['id' => $payment->id]) }}"><i
                                                    class="fas fa-trash fa-lg"> </i>
                                            </a>
                                        </div>
                                        <div class="col-12 d-flex align-items-center">
                                            <div class="d-flex align-items-center">
                                                <b class="mb-4 mx-2">{{ t('show_on_invoice') }}:</b>
                                                <label class="switch m-0">
                                                    <input type="checkbox"
                                                        class="show-on-invoice invoice-checkbox-{{ $payment->id }} @error('show_on_invoice') is-invalid @enderror"
                                                        name="show_on_invoice" value="{{ $payment->id }}"
                                                        {{ $payment->show_on_invoice ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                    @error('show_on_invoice')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
        .payment-button {
            margin-bottom: 20px !important;
        }

        .col-form-label {
            font-weight: bold;
        }

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
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }
        input:checked + .slider:before {
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
        .text-warning {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
    <script src="{{ url('js/fileinput/locales/' . config('app.locale') . '.js') }}" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Default payment toggle
            $(".switchboxes").change(function() {
                if (!$(this).prop('checked')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'There should be only one default payment!',
                    });
                    $(this).prop('checked', true);
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: "{{ route('update-default-payment') }}",
                    data: {
                        default_payment: $(this).prop('checked') ? $(this).val() : 0,
                        payment_id: $(this).val()
                    },
                    success: function() {
                        window.location.reload();
                    }
                });
            });

            // Delete payment
            $('.delete-payment').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var id = $(this).data('id');

                if ($('.' + id).prop('checked')) {
                    Swal.fire({
                        icon: 'warning',
                        title: "{{ trans('Oops...') }}",
                        text: "{{ trans('You can not delete default selected Payment!') }}",
                    });
                    return false;
                }

                Swal.fire({
                    title: "{{ trans('Are you sure you want to delete this Payment?') }}",
                    text: "{{ trans('You wont be able to revert this!') }}",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: "{{ trans('Yes, delete it!') }}"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "delete",
                            url: url,
                            success: function() {
                                window.location.reload();
                            }
                        });
                    }
                });
            });

            // Show on invoice toggle
            $(".show-on-invoice").change(function() {
                var $checkbox = $(this);
                
                if ($checkbox.prop('checked')) {
                    // Uncheck all other checkboxes
                    $(".show-on-invoice").not($checkbox).prop('checked', false);
                    
                    // Update the UI immediately
                    $(".show-on-invoice").not($checkbox).closest('.slider.round').prev().prop('checked', false);
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('update-show-on-invoice') }}",
                    data: {
                        show_on_invoice: $checkbox.prop('checked') ? 1 : 0,
                        payment_id: $checkbox.val()
                    },
                    success: function(response) {
                        if(response.success) {
                            $('#avatarUploadSuccess').text(response.message).fadeIn().delay(3000).fadeOut();
                            if ($checkbox.prop('checked')) {
                                $(".show-on-invoice").not($checkbox).prop('checked', false);
                            }
                        }
                    },
                    error: function() {
                        $checkbox.prop('checked', !$checkbox.prop('checked'));
                    }
                });
            });
        });

        function confirmShowOnInvoice(checkbox) {
            var $checked = $(".show-on-invoice:checked");
            if ($checked.length > 0 && $checked[0] != checkbox) {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ t("Warning") }}',
                    text: '{{ t("Only one payment method can be shown on invoice. Enabling this will disable the others.") }}',
                    showCancelButton: true,
                    confirmButtonText: '{{ t("Continue") }}',
                    cancelButtonText: '{{ t("Cancel") }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Uncheck all others
                        $(".show-on-invoice").prop('checked', false);
                        $(checkbox).prop('checked', true);
                        
                        // Trigger the change event
                        $(checkbox).trigger('change');
                    } else {
                        $(checkbox).prop('checked', false);
                    }
                });
                return false;
            }
            return true;
        }
    </script>
@endsection