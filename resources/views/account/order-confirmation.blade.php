@extends('layouts.master')

@section('content')
    <div class="main-container">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Success Header -->
                    <div class="text-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="#28a745" class="bi bi-check-circle-fill mb-4" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        <h2 class="fw-bold text-success">{{ t('Order Confirmation') }}</h2>
                        <div class="alert alert-success">
                            {{ t('Your order has been placed successfully!') }}
                        </div>
                    </div>

                    <!-- Order Items Card -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h4 class="h5 mb-4">{{ t('Order Items') }}</h4>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>{{ t('product') }}</th>
                                            <th class="text-center">{{ t('quantity') }}</th>
                                            <th class="text-center">{{ t('price') }}</th>
                                            <th class="text-center">{{ t('Share Location') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <?php
                                                // Process addons
                                                $addons = $item->getAddonsFormattedAttribute() ?? [];

                                                // Process duration
                                                $durationDisplay = '';
                                                if ($item->duration) {
                                                    $minutes = $item->duration->duration_value;
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

                                                // Process time slots
                                                $timeSlots = $item->getTimeSlotsFormattedAttribute() ?? [];

                                                // Get shipping address title
                                                $addressTitle = '';
                                                if ($item->duration && $item->duration->location_id) {
                                                    $address = $durationAddresses[$item->duration->location_id] ?? null;
                                                    $addressTitle = $address->address_title ?? '';
                                                }
                                            ?>
                                            <tr>
                                                <td class="small">
                                                    <!-- Product Title -->
                                                    <strong>
                                                        <a href="{{ \App\Helpers\UrlGen::post($item->post) }}" 
                                                           title="{{ $item->post->title }}">
                                                            {{ \Illuminate\Support\Str::limit($item->post->title ?? 'Product', 40) }}
                                                        </a>
                                                    </strong>
                                                    <!-- Addons -->
                                                    @if($addons)
                                                        <ul class="mt-2">
                                                            @foreach($addons as $addon)
                                                                <li><strong>{{ $addon['title'] ?? 'Addon' }} - ${{ number_format($addon['price'] ?? 0, 2) }}</strong></li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="mt-2">{{ t('no_addons') }}</p>
                                                    @endif
                                                    <!-- Duration and Address -->
                                                    @if($durationDisplay)
                                                        <div class="mt-2">
                                                            <strong>{{ t('Time and Address') }}:</strong><br>
                                                            <span><b>{{ t('period') }}:</b> ({{ $durationDisplay }})</span><br>
                                                            <span><b>{{ t('Address') }}:</b> {{ $addressTitle }}</span>
                                                        </div>
                                                    @endif
                                                    <!-- Time Slots -->
                                                    @if(!empty($timeSlots))
                                                        <div class="mt-2">
                                                            <strong>{{ t('Time Range') }}:</strong>
                                                            <ul class="list-unstyled time-slots">
                                                                @foreach($timeSlots as $slot)
                                                                    <li>
                                                                        <i class="far fa-clock"></i> 
                                                                        {{ $slot['day'] }}: 
                                                                        {{ $slot['open_time'] }} - {{ $slot['close_time'] }}
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">{{ $item->quantity }}</td>
                                                <td class="text-center align-middle">${{ number_format($item->total_price, 2) }}</td>
                                                <td class="text-center align-middle">{{ $item->share_location ? t('Yes') : t('No') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-end mt-4">
                                <a href="/" class="btn btn-primary">{{ t('continue_shopping') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 8px;
        }
        .table th {
            background-color: #f8f9fa;
            text-transform: capitalize;
        }
        .badge {
            font-size: 0.85em;
        }
        .time-slots li {
            margin-bottom: 5px;
        }
        .time-slots i {
            margin-right: 5px;
        }
    </style>
@endpush