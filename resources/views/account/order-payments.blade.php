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
                                    <a class="nav-link active" id="paid-tab" data-toggle="tab" href="#paid" role="tab" aria-controls="paid" aria-selected="true" onclick="loadPaidOrders()">{{ t('Paid') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="received-tab" data-toggle="tab" href="#received" role="tab" aria-controls="received" aria-selected="false" onclick="loadReceivedOrders()">{{ t('Received') }}</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-4">
                            <div class="tab-content" id="orderTabsContent">
                                <!-- Paid Tab -->
                                <div class="tab-pane fade show active" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                                    <div class="text-center py-4" id="paid-loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading paid orders...</p>
                                    </div>
                                    <div class="table-responsive" id="paid-orders-container" style="display: none;">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center"> {{ t('Orders') }}</th>
                                                    <th> {{ t('Date') }}</th>
                                                    <th>{{ t('Description') }}</th>
                                                    <th class="text-center">{{ t('total') }}</th>
                                                    <th class="text-center">{{ t('details') }}</th> 
                                                </tr>
                                            </thead>
                                            <tbody id="paid-orders-body">
                                                <!-- AJAX content will be loaded here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">{{ t('total_paid') }}</td>
                                                    <td class="text-center font-weight-bold" id="paid-total-amount">$0.00</td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div id="paid-no-orders" class="text-center py-4" style="display: none;">
                                            No paid orders found.
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Received Tab -->
                                <div class="tab-pane fade" id="received" role="tabpanel" aria-labelledby="received-tab">
                                    <div class="text-center py-4" id="received-loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="mt-2">Loading received payments...</p>
                                    </div>
                                    <div class="table-responsive" id="received-orders-container" style="display: none;">
                                        <table class="table table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="text-center"> {{ t('Orders') }}</th>
                                                    <th> {{ t('Date') }}</th>
                                                    <th>{{ t('Description') }}</th>
                                                    <th class="text-center">{{ t('total') }}</th>
                                                    <th class="text-center">{{ t('details') }}</th> 
                                                </tr>
                                            </thead>
                                            <tbody id="received-orders-body">
                                                <!-- AJAX content will be loaded here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">{{ t('total_recieved') }}</td>
                                                    <td class="text-center font-weight-bold" id="received-total-amount">$0.00</td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div id="received-no-orders" class="text-center py-4" style="display: none;">
                                            No received payments found.
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
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 1.5rem;
        }
        .card-header .nav-tabs {
            border-bottom: none;
        }
        .card-header .nav-tabs .nav-link {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            color: #495057;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
            cursor: pointer;
        }
        .card-header .nav-tabs .nav-link.active {
            color: #007bff;
            border-bottom: 3px solid #007bff;
            background: transparent;
        }
        .card-header .nav-tabs .nav-link:hover {
            color: #007bff;
            border-bottom: 3px solid #007bff;
        }
        .table {
            margin-bottom: 0;
            border-color: #e0e0e6;
        }
        .table th {
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
            white-space: nowrap;
            vertical-align: middle;
            padding: 1rem;
        }
        .table td {
            vertical-align: middle;
            padding: 1rem;
        }
        .table tfoot tr {
            background-color: #f8f9fa;
        }
        .table tfoot td {
            padding: 1rem;
            border-top: 2px solid #dee2e6;
        }
        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.875em;
            font-weight: 600;
            border-radius: 0.25rem;
        }
        .bg-success {
            background-color: #28a745 !important;
        }
        .time-slots li {
            margin-bottom: 0.25rem;
        }
        .time-slots i {
            margin-right: 0.5rem;
            color: #6c757d;
        }
        .text-success {
            color: #28a745 !important;
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
        
        @media (max-width: 767px) {
            .page-sidebar {
                margin-bottom: 1.5rem;
            }
            .card-header .nav-tabs .nav-link {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                border: 1px solid #dee2e6;
                border-radius: 4px;
            }
            .table td, .table th {
                padding: 0.75rem;
                font-size: 0.9rem;
            }
            .table tfoot td {
                padding: 0.75rem;
            }
        }
    </style>
@endsection

@section('after_scripts')
    <script>
        // Load paid orders when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadPaidOrders();
        });

        function loadPaidOrders() {
            $('#paid-loading').show();
            $('#paid-orders-container').hide();
            $('#paid-no-orders').hide();

            $.ajax({
                url: '{{ route("account.orders.purchased") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#paid-loading').hide();
                    
                    if (response.data && response.data.length > 0) {
                        let html = '';
                        let totalAmount = 0;
                        
                        response.data.forEach(function(order) {
                            order.items.forEach(function(item) {
                                const itemTotal = parseFloat(item.total_price) || 0;
                                totalAmount += itemTotal;
                                
                                html += `
                                <tr>
                                    <td class="text-center align-middle">#${order.id}</td>
                                    <td class="align-middle">${new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                                    <td class="small">
                                        <strong>
                                            <a href="${item.post_url}" title="${item.post_title}">
                                                ${item.post_title || 'N/A'}
                                            </a>
                                        </strong>`;
                                
                                if (item.time_slots_formatted && item.time_slots_formatted.length > 0) {
                                    html += `
                                        <div class="mt-2">
                                            <strong>Time Range:</strong>
                                            <ul class="list-unstyled time-slots mb-0">`;
                                    
                                    item.time_slots_formatted.forEach(function(slot) {
                                        html += `
                                            <li>
                                                <i class="far fa-clock"></i> 
                                                ${slot.day || ''}: 
                                                ${slot.open_time || ''} - ${slot.close_time || ''}
                                            </li>`;
                                    });
                                    
                                    html += `
                                            </ul>
                                        </div>`;
                                }
                                
                                html += `
                                    </td>
                                    <td class="text-center align-middle">$${itemTotal.toFixed(2)}</td>
                                    <td class="text-center align-middle">
                                        <a href="{{ url('account/orders') }}/${order.id}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ url('account/orders/sales') }}/${order.id}/download-invoice" class="btn btn-sm btn-download" title="Download Invoice">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                </tr>`;
                            });
                        });
                        
                        $('#paid-orders-body').html(html);
                        $('#paid-total-amount').text('$' + totalAmount.toFixed(2));
                        $('#paid-orders-container').show();
                    } else {
                        $('#paid-no-orders').show();
                    }
                },
                error: function(xhr) {
                    $('#paid-loading').hide();
                    $('#paid-no-orders').show();
                    console.error('Error loading paid orders:', xhr.responseText);
                }
            });
        }

        function loadReceivedOrders() {
            $('#received-loading').show();
            $('#received-orders-container').hide();
            $('#received-no-orders').hide();

            $.ajax({
                url: '{{ route("account.orders.sales") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#received-loading').hide();
                    
                    if (response.data && response.data.length > 0) {
                        let html = '';
                        let totalAmount = 0;
                        
                        response.data.forEach(function(orderGroup) {
                            const order = orderGroup.order || {};
                            const items = orderGroup.items || [];
                            
                            items.forEach(function(item) {
                                const itemTotal = parseFloat(item.total_price) || 0;
                                totalAmount += itemTotal;
                                
                                html += `
                                <tr>
                                    <td class="text-center align-middle">#${order.id || 'N/A'}</td>
                                    <td class="align-middle">${order.created_at ? new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'N/A'}</td>
                                    <td class="small">
                                        <strong>
                                            <a href="${item.post_url || '#'}" title="${item.post_title || 'N/A'}">
                                                ${item.post_title || 'N/A'}
                                            </a>
                                        </strong>`;
                                
                                if (item.duration && item.duration.name) {
                                    html += `
                                        <div class="mt-2">
                                            <strong>Period:</strong> ${item.duration.name}
                                        </div>`;
                                }
                                
                                if (item.time_slots_formatted && Array.isArray(item.time_slots_formatted) && item.time_slots_formatted.length > 0) {
                                    html += `
                                        <div class="mt-2">
                                            <strong>Time Range:</strong>
                                            <ul class="list-unstyled time-slots mb-0">`;
                                    
                                    item.time_slots_formatted.forEach(function(slot) {
                                        html += `
                                        <li>
                                            <i class="far fa-clock"></i> 
                                            ${slot.day || ''}: 
                                            ${slot.open_time || ''} - ${slot.close_time || ''}
                                        </li>`;
                                    });
                                    
                                    html += `
                                        </ul>
                                    </div>`;
                                }
                                
                                html += `
                                    </td>
                                    <td class="text-center align-middle">$${itemTotal.toFixed(2)}</td>
                                   
                                    <td class="text-center align-middle">
                                        <a href="{{ url('account/orders/sales') }}/${order.id}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-eye"></i> 
                                        </a>
                                        <a href="{{ url('account/orders/sales') }}/${order.id}/download-invoice" class="btn btn-sm btn-download" title="Download Invoice">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </td>
                                </tr>`;
                            });
                        });
                        
                        $('#received-orders-body').html(html);
                        $('#received-total-amount').text('$' + totalAmount.toFixed(2));
                        $('#received-orders-container').show();
                    } else {
                        $('#received-no-orders').html('No received payments found.');
                        $('#received-no-orders').show();
                    }
                },
                error: function(xhr) {
                    $('#received-loading').hide();
                    $('#received-no-orders').html('Error loading received payments. Please try again later.');
                    $('#received-no-orders').show();
                    console.error('Error loading received payments:', xhr.responseText);
                }
            });
        }
    </script>
@endsection