@extends('layouts.master')

@php
    // Define status classes with their corresponding badge colors
    $statusClasses = [
        'verified' => 'bg-success',
        'premium' => 'bg-primary',
        'pending' => 'bg-warning',
        'new' => 'bg-info',
        'not verified' => 'bg-secondary',
        'default' => 'bg-secondary'
    ];
@endphp

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

                <div class="col-md-9 page-content">
                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ t('Order') }} #{{ $order->id }}</h4>
                               <div class="d-flex">
                                 <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                                 <span class="d-flex align-items-center">
                                    @if($type === 'purchased')
                                        <a href="{{ route('account.orders.download-invoice', $order->id) }}" 
                                           class="btn btn-sm btn-success ml-3" 
                                           title="{{ t('Download Invoice') }}">
                                           {{ t('Download') }}
                                        </a>
                                    @else
                                        <a href="{{ route('account.orders.sales.download-invoice', $order->id) }}" 
                                           class="btn btn-sm btn-success ml-3" 
                                           title="{{ t('Download Invoice') }}">
                                            {{ t('Download') }}
                                        </a>
                                    @endif
                                </span>
                               </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Order Information Section -->
                            <h5 class="mb-3">{{ t('Order Information') }}</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="30%">{{ t('Order ID') }}</th>
                                            <td>{{ $order->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('User ID') }}</th>
                                            <td>{{ $order->user_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Country Code') }}</th>
                                            <td>{{ $order->country_code ?? config('country.code') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('subtotal') }}</th>
                                            <td>${{ number_format($order->subtotal, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Tax') }}</th>
                                            <td>${{ number_format($order->tax, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Total Amount') }}</th>
                                            <td>${{ number_format($order->total_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Status') }}</th>
                                            <td>{{ ucfirst($order->status) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Payment Method') }}</th>
                                            <td>{{ ucfirst($order->payment_method) }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ t('Created At') }}</th>
                                            <td>{{ $order->created_at }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Order Items Section -->
                            <h5 class="mb-3">{{ t('Order Items') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>{{ t('Item Title') }}</th>
                                            <th>{{ t('Duration') }}</th>
                                            <th>{{ t('time_ranges') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                {{ $item->post->title ?? t('N/A') }}
                                            </td>
                                            <td>
                                                @if(!empty($item->duration))
                                                    <div class="duration-info">
                                                        <strong>{{ $item->duration->duration_title }}</strong>
                                                        @if(!empty($item->duration->duration_value))
                                                            <div class="text-muted small">{{ t('Duration') }} {{ $item->duration->duration_value }} days</div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">{{ t('N/A') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($item->time_slots_formatted))
                                                    <ul class="list-unstyled time-slots mb-0">
                                                        @foreach($item->time_slots_formatted as $slot)
                                                            <li>
                                                                <i class="far fa-clock"></i> 
                                                                {{ $slot['day'] ?? '' }}: 
                                                                {{ $slot['open_time'] ?? '' }} - {{ $slot['close_time'] ?? '' }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {{ t('N/A') }}
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- User Information Section -->
                            <h5 class="mb-3">{{ $type === 'purchased' ? t('seller_info') : t('buyer_info') }}</h5>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>{{ t('Name') }}</th>
                                            <th>{{ t('status') }}</th>
                                            <th>{{ t('address') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                @if($type === 'purchased')
                                                    {{ $order->items->first()->post->user->name ?? t('N/A') }}
                                                @else
                                                    {{ $order->user->name ?? t('N/A') }}
                                                @endif
                                            </td>
                                            <td>
                                                @php 
                                                    $userStatus = $type === 'purchased' 
                                                        ? ($order->items->first()->post->user->userStatus ?? null)
                                                        : ($order->user->userStatus ?? null);
                                                @endphp
                                                <div class="status-icon-container d-flex align-items-center" 
                                                     style="cursor: pointer;" 
                                                     onclick="showStatusModal('{{ $type === 'purchased' ? 'seller' : 'buyer' }}')">
                                                    @if($userStatus)
                                                        <img alt="{{ $userStatus->title }}" 
                                                             class="img-fluid rounded me-2" 
                                                             width="30"
                                                             src="{{ asset('storage/user_status_icons/' . $userStatus->icon) }}"
                                                             data-toggle="tooltip" 
                                                             data-placement="top" 
                                                             title="{{ $userStatus->title }}">
                                                        <span class="badge {{ $statusClasses[strtolower($userStatus->status)] ?? $statusClasses['default'] }}">
                                                            {{ \App\Models\UserStatus::STATUS_OPTIONS[strtolower($userStatus->status)] ?? ucfirst($userStatus->status) }}
                                                        </span>
                                                    @else
                                                        <img src="{{ imgUrl(config('larapen.core.picture.default'), 'medium') }}" 
                                                             class="img-fluid rounded me-2" 
                                                             width="30"
                                                             alt="{{ t('Default Status') }}">
                                                        <span class="badge bg-secondary">
                                                            {{ t('Not Verified') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($type === 'purchased')
                                                    @if($order->items->first()->share_location && $order->shippingAddress)
                                                        {{ $order->shippingAddress->address ?? t('N/A') }}
                                                    @else
                                                        {{ t('Location not shared') }}
                                                    @endif
                                                @else
                                                    @if($order->items->first()->share_location && $order->shippingAddress)
                                                        {{ $order->shippingAddress->address ?? t('N/A') }}
                                                    @else
                                                        {{ t('Location not shared') }}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <a href="{{route('account.orders') }}" class="btn btn-primary">
                                    <i class="fa fa-arrow-left"></i> {{ t('Back to Orders') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Single Status Modal for both buyer and seller -->
    <div class="modal fade" id="userStatusModal" tabindex="-1" role="dialog" aria-labelledby="userStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userStatusModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ t('Close') }}">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="status-icon mb-3">
                        <img id="statusModalIcon" class="img-fluid" style="max-height: 100px" alt="">
                    </div>
                    <div class="status-message mb-4" id="statusModalMessage"></div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_scripts')
    <script>
        function showStatusModal(type) {
            const modal = $('#userStatusModal');
            const userStatus = type === 'seller' 
                ? @json($order->items->first()->post->user->userStatus ?? null)
                : @json($order->user->userStatus ?? null);
            
            if (!userStatus) {
                $('#userStatusModalLabel').text("{{ t('Status Not Available') }}");
                $('#statusModalIcon').attr('src', "{{ imgUrl(config('larapen.core.picture.default'), 'medium') }}");
                $('#statusModalIcon').attr('alt', "{{ t('Default Status') }}");
                $('#statusModalMessage').html("<p>{{ t('No status information available.') }}</p>");
                modal.modal('show');
                return;
            }

            // Set modal content based on status
            const { title, message, proceedText } = getStatusContent(userStatus.status);
            $('#userStatusModalLabel').text(title);
            $('#statusModalIcon').attr('src', "{{ asset('storage/user_status_icons/') }}/" + userStatus.icon);
            $('#statusModalIcon').attr('alt', userStatus.title);
            $('#statusModalMessage').html(message);
            $('#proceedButton').text(proceedText).prop('disabled', true);
            $('#understandRisks').prop('checked', false);

            // Enable/disable proceed button based on checkbox
            $('#understandRisks').on('change', function() {
                $('#proceedButton').prop('disabled', !this.checked);
            });
            
            // Show modal
            modal.modal('show');
        }

        function getStatusContent(status) {
            switch (status.toLowerCase()) {
                case 'not verified':
                    return {
                        title: "{{ t('not_verified_title') }}",
                        message: `<p>{{ t('not_verfied_info') }}</p>
                                <p>{{ t('Proceeding with caution is advised.') }}</p>`,
                        proceedText: "{{ t('Proceed with Booking') }}"
                    };
                case 'new':
                    return {
                        title: "{{ t('new_user') }}",
                        message: `<p>{{ t('new_user_status_info') }}</p>
                                <p>{{ t('Payment via platform will only be available after verification.') }}</p>`,
                        proceedText: "{{ t('Proceed with Booking') }}"
                    };
                case 'pending':
                    return {
                        title: "{{ t('Verification Pending') }}",
                        message: `<p>{{ t('user_pending_status_info') }}</p>
                                <p>{{ t('Verification typically takes 1-3 business days.') }}</p>`,
                        proceedText: "{{ t('Proceed with Booking') }}"
                    };
                case 'verified':
                    return {
                        title: "{{ t('Verified User') }}",
                        message: `<p>{{ t('verified_user_status_info') }}</p>
                                <p>{{ t('Their identity and contact information have been confirmed.') }}</p>`,
                        proceedText: "{{ t('Book with Confidence') }}"
                    };
                case 'premium':
                    return {
                        title: "{{ t('Premium User') }}",
                        message: `<p>{{ t('premium_user_status_info') }}</p>
                                <p>{{ t('They have met our highest standards for reliability and service quality.') }}</p>`,
                        proceedText: "{{ t('Book with Confidence') }}"
                    };
                default:
                    return {
                        title: "{{ t('Unknown Status') }}",
                        message: `<p>{{ t('No specific status information is available.') }}</p>`,
                        proceedText: "{{ t('Proceed with Booking') }}"
                    };
            }
        }
    </script>
@endsection

@section('after_styles')
    <style>
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,.125);
        }
        .table th {
            background-color: #f8f9fa;
        }
        .badge {
            font-size: 0.875em;
            font-weight: 600;
            padding: 0.35em 0.65em;
        }
        .bg-success {
            background-color: #28a745 !important;
        }
        .bg-primary {
            background-color: #007bff !important;
        }
        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529;
        }
        .bg-info {
            background-color: #17a2b8 !important;
        }
        .bg-secondary {
            background-color: #6c757d !important;
        }
        .status-icon-container {
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
        }
        .status-icon-container:hover {
            transform: scale(1.05);
        }
        .status-icon-container img {
            border: 2px solid #eee;
        }
        .status-icon-container:hover img {
            border-color: #ddd;
        }
        h5 {
            font-weight: 600;
            color: #495057;
        }
        .bg-light {
            background-color: #f8f9fa !important;
        }
        .status-message p {
            margin-bottom: 0.5rem;
        }
        .duration-info {
            line-height: 1.4;
        }
        .time-slots li {
            margin-bottom: 0.25rem;
        }
        .time-slots i {
            margin-right: 0.5rem;
            color: #6c757d;
        }
        .form-check-input {
            margin-right: 0.5rem;
        }
        .form-check-label {
            font-size: 0.9em;
        }
        .table-borderless td,
        .table-borderless th {
            border: none;
            padding: 0.5rem;
        }
    </style>
@endsection