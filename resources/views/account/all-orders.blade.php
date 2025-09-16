@extends('layouts.master')

@section('content')
    @includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
    <div class="main-container">
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
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
                        <div class="card-header bg-white border-bottom">
                            <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="purchased-tab" data-toggle="tab" href="#purchased" role="tab" aria-controls="purchased" aria-selected="true" onclick="loadPurchasedOrders()">{{ t('Purchased') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="false" onclick="loadSalesOrders()">{{ t('Sales') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content" id="orderTabsContent">
                                <!-- Purchased Tab -->
                                <div class="tab-pane fade show active" id="purchased" role="tabpanel" aria-labelledby="purchased-tab">
                                    <div class="text-center py-4" id="purchased-loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading purchased orders...</p>
                                    </div>
                                    <div class="table-responsive" id="purchased-orders-container" style="display: none;">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center"> {{ t('Order ID') }}</th>
                                                    <th> {{ t('Date') }}</th>
                                                    <th>{{ t('Order Items') }}</th>
                                                    <th class="text-center">{{ t('total') }}</th>
                                                    <th class="text-center">{{ t('Status') }}</th>
                                                    <th class="text-center">{{ t('details') }}</th> 
                                                </tr>
                                            </thead>
                                            <tbody id="purchased-orders-body">
                                                <!-- AJAX content will be loaded here -->
                                            </tbody>
                                        </table>
                                        <div id="purchased-no-orders" class="text-center py-4" style="display: none;">
                                            No purchased orders found.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Sales Tab -->
                                <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                                    <div class="text-center py-4" id="sales-loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading sales orders...</p>
                                    </div>
                                    <div class="table-responsive" id="sales-orders-container" style="display: none;">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center"> {{ t('Order ID') }}</th>
                                                    <th> {{ t('Date') }}</th>
                                                    <th>{{ t('Order Items') }}</th>
                                                    <th class="text-center">{{ t('total') }}</th>
                                                    <th>{{ t('Buyer') }}</th>
                                                    <th class="text-center">{{ t('Status') }}</th>
                                                    <th class="text-center">{{ t('details') }}</th> 
                                                </tr>
                                            </thead>
                                            <tbody id="sales-orders-body">
                                                <!-- AJAX content will be loaded here -->
                                            </tbody>
                                        </table>
                                        <div id="sales-no-orders" class="text-center py-4" style="display: none;">
                                            No sales found.
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
        .order-item {
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .item-title {
            font-weight: 600;
        }
        .item-details {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .time-slot {
            display: flex;
            align-items: center;
            margin-bottom: 3px;
        }
        .time-slot i {
            margin-right: 5px;
        }
        .btn-download {
            background-color: #28a745;
            color: white;
            margin-left: 5px;
        }
        .btn-download:hover {
            background-color: #218838;
            color: white;
        }
        .action-buttons {
            display: flex;
            justify-content: center;
        }
    </style>
@endsection

@section('after_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadPurchasedOrders();
        });

        function loadPurchasedOrders() {
            $('#purchased-loading').show();
            $('#purchased-orders-container').hide();
            $('#purchased-no-orders').hide();

            $.ajax({
                url: '{{ route("account.orders.purchased") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#purchased-loading').hide();
                    
                    if (response.data && response.data.length > 0) {
                        let html = '';
                        response.data.forEach(function(order) {
                            let itemsHtml = '';
                            let totalPrice = 0;
                            
                            order.items.forEach(function(item, index) {
                                totalPrice += parseFloat(item.total_price);
                                
                                itemsHtml += `
                                <div class="order-item">
                                    <div class="item-title">
                                        <a href="${item.post_url}" title="${item.post_title}">
                                            ${item.post_title || 'N/A'}
                                        </a>
                                    </div>
                                    <div class="item-details">
                                        <div>Quantity: ${item.quantity}</div>
                                        <div>Price: $${parseFloat(item.total_price).toFixed(2)}</div>`;
                                
                                if (item.time_slots_formatted && item.time_slots_formatted.length > 0) {
                                    itemsHtml += `<div class="mt-1"><strong>Time Slots:</strong></div>`;
                                    item.time_slots_formatted.forEach(function(slot) {
                                        itemsHtml += `
                                        <div class="time-slot">
                                            <i class="far fa-clock"></i>
                                            ${slot.day || ''}: ${slot.open_time || ''} - ${slot.close_time || ''}
                                        </div>`;
                                    });
                                }
                                
                                itemsHtml += `</div></div>`;
                            });
                            
                            html += `
                            <tr>
                                <td class="text-center align-middle">#${order.id}</td>
                                <td class="align-middle">${new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                                <td class="small">${itemsHtml}</td>
                                <td class="text-center align-middle">$${totalPrice.toFixed(2)}</td>
                                <td class="text-center align-middle">
                                    <span class="badge bg-success">${order.status.charAt(0).toUpperCase() + order.status.slice(1)}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="action-buttons">
                                        <a href="{{ url('account/orders') }}/${order.id}" class="btn btn-sm btn-primary" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ url('account/orders') }}/${order.id}/download-invoice" class="btn btn-sm btn-download" title="Download Invoice">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
                        });
                        
                        $('#purchased-orders-body').html(html);
                        $('#purchased-orders-container').show();
                    } else {
                        $('#purchased-no-orders').show();
                    }
                },
                error: function(xhr) {
                    $('#purchased-loading').hide();
                    $('#purchased-no-orders').show();
                    console.error('Error loading purchased orders:', xhr.responseText);
                }
            });
        }

        function loadSalesOrders() {
            $('#sales-loading').show();
            $('#sales-orders-container').hide();
            $('#sales-no-orders').hide();

            $.ajax({
                url: '{{ route("account.orders.sales") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#sales-loading').hide();
                    
                    if (response.data && response.data.length > 0) {
                        let html = '';
                        response.data.forEach(function(orderGroup) {
                            const order = orderGroup.order || {};
                            const items = orderGroup.items || [];
                            let itemsHtml = '';
                            let totalPrice = 0;
                            
                            items.forEach(function(item) {
                                totalPrice += parseFloat(item.total_price);
                                
                                itemsHtml += `
                                <div class="order-item">
                                    <div class="item-title">
                                        <a href="${item.post_url || '#'}" title="${item.post_title || 'N/A'}">
                                            ${item.post_title || 'N/A'}
                                        </a>
                                    </div>
                                    <div class="item-details">
                                        <div>Quantity: ${item.quantity || 'N/A'}</div>
                                        <div>Price: $${parseFloat(item.total_price).toFixed(2)}</div>`;
                                
                                if (item.duration && item.duration.name) {
                                    itemsHtml += `<div>Period: ${item.duration.name}</div>`;
                                }
                                
                                if (item.time_slots_formatted && Array.isArray(item.time_slots_formatted) && item.time_slots_formatted.length > 0) {
                                    itemsHtml += `<div class="mt-1"><strong>Time Slots:</strong></div>`;
                                    item.time_slots_formatted.forEach(function(slot) {
                                        itemsHtml += `
                                        <div class="time-slot">
                                            <i class="far fa-clock"></i>
                                            ${slot.day || ''}: ${slot.open_time || ''} - ${slot.close_time || ''}
                                        </div>`;
                                    });
                                }
                                
                                itemsHtml += `</div></div>`;
                            });
                            
                            html += `
                            <tr>
                                <td class="text-center align-middle">#${order.id || 'N/A'}</td>
                                <td class="align-middle">${order.created_at ? new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A'}</td>
                                <td class="small">${itemsHtml}</td>
                                <td class="text-center align-middle">$${totalPrice.toFixed(2)}</td>
                                <td class="align-middle">
                                    <strong>${order.user_name || 'N/A'}</strong>
                                </td>
                                <td class="text-center align-middle">
                                    <span class="badge bg-success">${order.status ? order.status.charAt(0).toUpperCase() + order.status.slice(1) : 'N/A'}</span>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="action-buttons">
                                        <a href="{{ url('account/orders/sales') }}/${order.id}" class="btn btn-sm btn-primary" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ url('account/orders/sales') }}/${order.id}/download-invoice" class="btn btn-sm btn-download" title="Download Invoice">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>`;
                        });
                        
                        $('#sales-orders-body').html(html);
                        $('#sales-orders-container').show();
                    } else {
                        $('#sales-no-orders').html('No sales orders found.');
                        $('#sales-no-orders').show();
                    }
                },
                error: function(xhr) {
                    $('#sales-loading').hide();
                    $('#sales-no-orders').html('Error loading sales orders. Please try again later.');
                    $('#sales-no-orders').show();
                    console.error('Error loading sales orders:', xhr.responseText);
                }
            });
        }
    </script>
@endsection