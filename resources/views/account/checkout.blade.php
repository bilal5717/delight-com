@extends('layouts.master')

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])

    <div class="main-container">
        <div class="floating-notification bg-success" id="notificationContainer"></div>
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8 mb-4">
                    <!-- Order Items -->
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h5 class="mb-3">
                                <a href="{{ url()->previous() }}" class="text-body">
                                    <i class="fas fa-long-arrow-alt-left me-2"></i>{{ t('Back') }}
                                </a>
                            </h5>
                            <hr>
                            @if(isset($cartItems) && $cartItems->isNotEmpty())
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ t('user_status') }}</th>
                                            <th>{{ t('description') }}</th>
                                            <th class="text-center">{{ t('Share Location') }}</th>
                                            <th>{{ t('quantity') }}</th>
                                            <th>{{ t('price') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cartItems as $item)
                                            <?php
                                                $addonsTotal = 0;
                                                $addons = is_string($item->addons) ? json_decode($item->addons, true) : (is_array($item->addons) ? $item->addons : []);
                                                if (is_array($addons)) {
                                                    $addonsTotal = array_sum(array_column($addons, 'price'));
                                                }
                                                $itemTotal = ($item->total_price + $addonsTotal) * $item->quantity;

                                                $postUrl = \App\Helpers\UrlGen::post($item->post);
                                                $timeSlots = is_array($item->time_slots) ? $item->time_slots : (is_string($item->time_slots) ? json_decode($item->time_slots, true) : []);
                                                $duration = isset($durations[$item->duration_id]) ? $durations[$item->duration_id] : null;
                                                $durationDisplay = '';
                                                if ($duration) {
                                                    $minutes = $duration->duration_value;
                                                    $days = floor($minutes / 1440);
                                                    $hours = floor(($minutes % 1440) / 60);
                                                    if ($days > 0) {
                                                        $durationDisplay = $days . ' ' . t('day') . ($days > 1 ? 's' : '');
                                                        if ($hours > 0) {
                                                            $durationDisplay .= ' ' . $hours . ' ' . t('hour') . ($hours > 1 ? 's' : '');
                                                        }
                                                    } else {
                                                        $durationDisplay = $hours . ' ' . t('hour') . ($hours > 1 ? 's' : '');
                                                    }
                                                }
                                            ?>
                                            <tr>
                                                <td class="text-center align-middle">
                                                    <div class="status-icon-container" style="cursor: pointer;" onclick="showSellerStatusModal()">
                                                        @if($user_status)
                                                            <img alt="{{ $user_status->title }}" 
                                                                 class="img-fluid rounded" 
                                                                 width="150px"
                                                                 src="{{ asset('storage/user_status_icons/' . $user_status->icon) }}"
                                                                 data-toggle="tooltip" 
                                                                 data-placement="top" 
                                                                 title="{{ t('Click for details') }} - {{ $user_status->title }}">
                                                        @else
                                                            <img src="{{ imgUrl(config('larapen.core.picture.default'), 'medium') }}" 
                                                                 class="img-fluid rounded-3" 
                                                                 alt="{{ t('Default Status') }}">
                                                            <span class="status-title">{{ t('No Status') }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="small">
                                                    <strong>
                                                        <a href="{{ $postUrl }}" title="{{ $item->post->title }}">
                                                            {{ \Illuminate\Support\Str::limit($item->post->title, 40) }}
                                                        </a>
                                                    </strong>
                                                    @if($addons && is_array($addons))
                                                        <ul class="mt-2">
                                                            @foreach($addons as $addon)
                                                                <li><strong>{{ $addon['addonTitle'] ?? t('Addon') }} - ${{ number_format($addon['price'] ?? 0, 2) }}</strong></li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="mt-2">{{ t('no_addons') }}</p>
                                                    @endif
                                                    @if($duration)
                                                        <div class="mt-2">
                                                            <strong>{{ t('Time and Address') }}:</strong><br>
                                                            <span><b>{{ t('period') }}:</b> ({{ $durationDisplay }})</span><br>

                                                            @if(isset($durationAddresses[$duration->location_id]))
                                                                @php
                                                                    $addr = $durationAddresses[$duration->location_id];
                                                                @endphp
                                                                <span>
                                                                    <b>{{ t('Address') }}:</b>
                                                                    {{ $addr->address_title ?? '' }}
                                                                </span><br>
                                                            @else
                                                                <span><b>{{ t('Address') }}:</b> {{ t('No address linked to this duration') }}</span><br>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if(!empty($timeSlots) && is_array($timeSlots))
                                                        <div class="mt-2">
                                                            <strong>{{ t('Time Range') }}:</strong>
                                                            <ul class="list-unstyled time-slots">
                                                                @foreach($timeSlots as $slot)
                                                                    @if(is_array($slot) && isset($slot['day']))
                                                                        <li>
                                                                            <i class="far fa-clock"></i> 
                                                                            {{ t($slot['day']) }}: 
                                                                            {{ $slot['open_time'] ?? '' }} - {{ $slot['close_time'] ?? '' }}
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle text-center">
                                                    <div class="custom-toggle mx-auto d-flex justify-content-center">
                                                        <input type="checkbox" 
                                                               id="shareLocation{{ $item->id }}"
                                                               name="share_location[{{ $item->id }}]"
                                                               value="1"
                                                               checked>
                                                        <label for="shareLocation{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">{{ $item->quantity }}</td>
                                                <td class="align-middle text-center">${{ number_format($itemTotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-warning">
                                    {{ t('No items in this cart') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping Address Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="mb-1">{{ t('service_delivery_address') }}</h3>
                            <p>
                                {{ t('shipping_address_info') }}
                            </p>
                            @if($shippingAddresses->isNotEmpty())
                                @foreach($shippingAddresses as $address)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="shippingAddress" 
                                               id="address{{ $address->id }}" 
                                               value="{{ $address->id }}"
                                               {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label d-block ps-2" for="address{{ $address->id }}">
                                            {{ $address->address_title ?? t('Address') }},
                                            {{ $address->address ?? '' }}, 
                                            {{ $address->city->name ?? '' }}, 
                                            {{ $address->country->name ?? '' }}
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-exclamation-circle"></i> 
                                    {{ t('No shipping addresses available') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Method Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h3 class="mb-3">{{ t('Payment Method') }}</h3>
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="paymentMethod" 
                                       id="paymentMethodOffline" 
                                       value="offlinepayment"
                                       checked>
                                <label class="form-check-label d-block ps-2" for="paymentMethodOffline">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    {{ t('offline_payment') }}
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="paymentMethod" 
                                       id="paymentMethodCard" 
                                       value="card">
                                <label class="form-check-label d-block ps-2" for="paymentMethodCard">
                                    <i class="fab fa-cc-visa me-2"></i>
                                    <i class="fab fa-cc-mastercard me-2"></i>
                                    {{ t('Visa/Mastercard') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{ t('order_summary') }}</h3>
                            <hr class="my-3">

                            <div class="d-flex justify-content-between mb-2">
                                <p class="mb-2">{{ t('subtotal') }}</p>
                                <span>${{ isset($cartItems) && $cartItems->isNotEmpty() ? number_format($cartItems->sum(function($item) {
                                    $addonsTotal = 0;
                                    $addons = is_string($item->addons) ? json_decode($item->addons, true) : (is_array($item->addons) ? $item->addons : []);
                                    if (is_array($addons)) {
                                        $addonsTotal = array_sum(array_column($addons, 'price'));
                                    }
                                    return ($item->total_price + $addonsTotal) * $item->quantity;
                                }), 2) : '0.00' }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <p class="mb-2">{{ t('shipping') }}</p>
                                <span>$5.00</span>
                            </div>

                            <hr class="my-2">

                            <div class="d-flex justify-content-between mb-3">
                                <p class="mb-2">{{ t('total_incl_tax') }}</p>
                                <strong>${{ isset($cartItems) && $cartItems->isNotEmpty() ? number_format($cartItems->sum(function($item) {
                                    $addonsTotal = 0;
                                    $addons = is_string($item->addons) ? json_decode($item->addons, true) : (is_array($item->addons) ? $item->addons : []);
                                    if (is_array($addons)) {
                                        $addonsTotal = array_sum(array_column($addons, 'price'));
                                    }
                                    return ($item->total_price + $addonsTotal) * $item->quantity;
                                }) + 5, 2) : '5.00' }}</strong>
                            </div>

                            <form action="{{ route('checkout.complete') }}" method="POST" id="complete-order-form">
                                @csrf
                                <input type="hidden" name="payment_method_id" id="selectedPaymentMethod" 
                                       value="offlinepayment">
                                <input type="hidden" name="shipping_address_id" id="selectedShippingAddress" 
                                       value="{{ $shippingAddresses->first()->id ?? '' }}">
                                <button type="submit" class="btn btn-primary w-100 py-2" 
                                        {{ $shippingAddresses->isEmpty() || !isset($cartItems) || $cartItems->isEmpty() ? 'disabled' : '' }}>
                                    {{ t('Complete Order') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  @if($user_status)
<!-- Seller Status Modal -->
<div class="modal fade" id="sellerStatusModal" tabindex="-1" role="dialog" aria-labelledby="sellerStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sellerStatusModalLabel">
                    @if($user_status->status === 'not verified')
                         {{ t('not_verified_title') }}
                    @elseif($user_status->status === 'new')
                       {{ t('new_user') }}
                    @elseif($user_status->status === 'pending')
                        {{ t('Verification Pending') }}
                    @elseif($user_status->status === 'verified')
                        {{ t('Verified User') }}
                    @elseif($user_status->status === 'premium')
                        {{ t('Premium User') }}
                    @endif
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ t('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="status-icon mb-3">
                    <img src="{{ asset('storage/user_status_icons/' . $user_status->icon) }}" 
                         class="img-fluid" 
                         style="max-height: 100px"
                         alt="{{ $user_status->title }}">
                </div>
                
                <div class="status-message mb-4">
                    @if($user_status->status === 'not verified')
                        <p>{{ t('not_verfied_info') }}</p>
                        <p>{{ t('Proceeding with caution is advised.') }}</p>
                    @elseif($user_status->status === 'new')
                        <p>{{ t('new_user_status_info') }}</p>
                        <p>{{ t('Payment via platform will only be available after verification.') }}</p>
                    @elseif($user_status->status === 'pending')
                        <p>{{ t('user_pending_status_info') }}</p>
                        <p>{{ t('Verification typically takes 1-3 business days.') }}</p>
                    @elseif($user_status->status === 'verified')
                        <p>{{ t('verified_user_status_info') }}</p>
                        <p>{{ t('Their identity and contact information have been confirmed.') }}</p>
                    @elseif($user_status->status === 'premium')
                        <p>{{ t('premium_user_status_info') }}</p>
                        <p>{{ t('They have met our highest standards for reliability and service quality.') }}</p>
                    @endif
                </div>
                
                <div class="form-check mb-3 d-flex align-items-center w-50 m-auto">
                    <input class="form-check-input" type="checkbox" id="understandRisks">
                    <label class="form-check-label mt-1" for="understandRisks">
                        {{ t('I understand the information above') }}
                    </label>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ t('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="proceedButton" disabled>
                    @if($user_status->status === 'verified' || $user_status->status === 'premium')
                        {{ t('Book with Confidence') }}
                    @else
                        {{ t('Proceed with Booking') }}
                    @endif
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('after_styles')
    <style>
        .custom-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .custom-toggle input[type="checkbox"] {
            display: none;
        }

        .custom-toggle label {
            width: 50px;
            height: 24px;
            background-color: #ccc;
            border-radius: 12px;
            position: relative;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .custom-toggle label::after {
            content: '';
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: transform 0.3s;
        }

        .custom-toggle input:checked + label {
            background-color: #28a745;
        }

        .custom-toggle input:checked + label::after {
            transform: translateX(26px);
        }

        .toggle-label {
            font-size: 0.9rem;
            color: #333;
        }

        .time-slots li {
            margin-bottom: 5px;
        }

        .time-slots i {
            margin-right: 5px;
        }
        
        .status-icon-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        /* Seller Status Modal Styles */
        #sellerStatusModal .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        #sellerStatusModal .modal-title {
            font-weight: 600;
            color: #333;
        }

        #sellerStatusModal .status-icon {
            margin: 0 auto 20px;
            max-width: 100px;
        }

        #sellerStatusModal .status-message p {
            margin-bottom: 15px;
            color: #555;
        }

        #sellerStatusModal .modal-footer {
            border-top: none;
            padding-top: 0;
        }

        #sellerStatusModal #proceedButton:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .status-icon-container:hover {
            opacity: 0.8;
        }

        .floating-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            display: none;
        }
    </style>
@endsection

@section('after_scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Show seller status modal when clicking status icon
        $('.status-icon-container').click(function() {
            $('#sellerStatusModal').modal('show');
        });

        // Enable/disable proceed button based on checkbox
        $('#understandRisks').change(function() {
            $('#proceedButton').prop('disabled', !this.checked);
        });

        // Handle proceed button click
        $('#proceedButton').click(function() {
            // Add any additional logic here before closing
            $('#sellerStatusModal').modal('hide');
        });

        // Payment method selection
        $('input[name="paymentMethod"]').change(function() {
            $('#selectedPaymentMethod').val($(this).val());
        });
        
        // Shipping address selection
        $('input[name="shippingAddress"]').change(function() {
            $('#selectedShippingAddress').val($(this).val());
        });

        // Ensure share_location is sent for all items
        $('#complete-order-form').submit(function(event) {
            $('input[name^="share_location"]:not(:checked)').each(function() {
                $('<input>').attr({
                    type: 'hidden',
                    name: this.name,
                    value: '0'
                }).appendTo('#complete-order-form');
            });
        });

        // Show notifications if present
        @if(session('success'))
            showNotification('success', '{{ session('success') }}');
        @elseif(session('error'))
            showNotification('error', '{{ session('error') }}');
        @endif
    });

    // Function to show notification
    function showNotification(type, message) {
        const $notification = $('#notificationContainer');
        $notification.text(message)
            .removeClass('bg-danger bg-success')
            .addClass(type === 'error' ? 'bg-danger' : 'bg-success')
            .fadeIn();
        
        setTimeout(() => {
            $notification.fadeOut();
        }, 3000);
    }
</script>
@endsection