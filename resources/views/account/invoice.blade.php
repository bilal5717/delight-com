<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        .header h1 {
            font-size: 22px;
            margin: 5px 0;
            color: #2c3e50;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            background-color: #28a745;
            color: white;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 5px 0;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .info-block {
            width: 48%;
            margin-bottom: 10px;
        }
        .info-block h3 {
            font-size: 14px;
            margin: 0 0 8px 0;
            color: #3498db;
            border-bottom: 1px dashed #eee;
            padding-bottom: 5px;
        }
        .info-block p {
            margin: 3px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11px;
        }
        .table th {
            background-color: #f8f9fa;
            padding: 8px 5px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        .table td {
            padding: 6px 5px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        .time-slot {
            margin: 2px 0;
            font-size: 10px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .summary-table {
            width: 200px;
            margin-left: auto;
            margin-top: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #777;
            text-align: center;
        }
        .logo {
            max-height: 40px;
            margin-bottom: 5px;
        }
        .payment-details {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #e0e0e0;
        }
        .payment-details h3 {
            font-size: 14px;
            margin: 0 0 10px 0;
            color: #3498db;
            border-bottom: 1px dashed #ddd;
            padding-bottom: 5px;
        }
        .payment-columns {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .payment-column {
            flex: 1;
            min-width: 200px;
        }
        .payment-row {
            margin: 5px 0;
        }
        .payment-method-icon {
            width: 24px;
            height: 24px;
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h1>INVOICE #{{ $order->id }}</h1>
            <div class="status-badge">{{ strtoupper($order->status) }}</div>
            <p>Date: {{ $order->created_at->format('M j, Y') }} | Payment: {{ ucfirst($order->payment_method) }}</p>
        </div>

        <div class="invoice-info">
            <div class="info-block">
                <h3>SELLER INFORMATION</h3>
                <p><strong>Name:</strong> {{ $type === 'purchased' ? ($order->items->first()->post->user->name ?? 'N/A') : auth()->user()->name }}</p>
                <p><strong>Status:</strong> {{ $type === 'purchased' ? ($order->items->first()->post->user->userStatus->title ?? 'Not Verified') : (auth()->user()->userStatus->title ?? 'Not Verified') }}</p>
                @if($type === 'purchased' && $order->items->first()->post->user->company)
                    <p><strong>Company:</strong> {{ $order->items->first()->post->user->company->name }}</p>
                @elseif($type === 'sales' && auth()->user()->company)
                    <p><strong>Company:</strong> {{ auth()->user()->company->name }}</p>
                @endif
            </div>
            
            <div class="info-block">
                <h3>CUSTOMER INFORMATION</h3>
                <p><strong>Name:</strong> {{ $type === 'purchased' ? auth()->user()->name : ($order->user->name ?? 'N/A') }}</p>
                <p><strong>Status:</strong> {{ $type === 'purchased' ? (auth()->user()->userStatus->title ?? 'Not Verified') : ($order->user->userStatus->title ?? 'Not Verified') }}</p>
                @if($type === 'sales' && $order->user->company)
                    <p><strong>Company:</strong> {{ $order->user->company->name }}</p>
                @endif
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40%">ITEM</th>
                    <th style="width: 10%">QTY</th>
                    <th style="width: 30%">TIME SLOTS</th>
                    <th style="width: 20%" class="text-right">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->post->title ?? 'N/A' }}</strong>
                        @if(!empty($item->duration))
                            <div style="color: #777; font-size: 10px;">
                                {{ $item->duration->duration_title }} ({{ $item->duration->duration_value }} days)
                            </div>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        @if(!empty($item->time_slots_formatted))
                            <div style="font-size: 10px;">
                                @foreach($item->time_slots_formatted as $slot)
                                    <div class="time-slot">
                                        {{ $slot['day'] ?? '' }}: {{ $slot['open_time'] ?? '' }} - {{ $slot['close_time'] ?? '' }}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="text-right">${{ number_format($item->total_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table summary-table">
            <tr>
                <th>Subtotal:</th>
                <td class="text-right">${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->tax > 0)
            <tr>
                <th>Tax:</th>
                <td class="text-right">${{ number_format($order->tax, 2) }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <th>TOTAL:</th>
                <td class="text-right">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>

        @php
    $seller = $type === 'purchased' ? ($order->items->first()->post->user ?? null) : auth()->user();
    $paymentDetails = null;
    
    if ($seller && $seller->company) {
        $paymentDetails = $seller->company->payments()
            ->where('show_on_invoice', true)
            ->first();
    }
@endphp

        @if($paymentDetails)
            <div class="payment-details">
                <h3>PAYMENT INFORMATION</h3>
                <div class="payment-columns">
                    <div class="payment-column">
                        <div class="payment-row">
                            <strong>Payment Method:</strong> 
                            @if($paymentDetails->currency_code === 'BANK')
                                <img src="{{ asset('images/bank-transfer-icon.png') }}" class="payment-method-icon" alt="Bank Transfer">
                                Bank Transfer
                            @elseif($paymentDetails->currency_code === 'PAYPAL')
                                <img src="{{ asset('images/paypal-icon.png') }}" class="payment-method-icon" alt="PayPal">
                                PayPal
                            @elseif($paymentDetails->currency_code === 'STRIPE')
                                <img src="{{ asset('images/credit-card-icon.png') }}" class="payment-method-icon" alt="Credit Card">
                                Credit Card
                            @else
                                {{ strtoupper($paymentDetails->currency_code) }}
                            @endif
                        </div>
                        
                        @if($paymentDetails->account_holder_name)
                        <div class="payment-row">
                            <strong>Account Holder:</strong> {{ $paymentDetails->account_holder_name }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->account_type !== null)
                        <div class="payment-row">
                            <strong>Account Type:</strong> 
                            {{ $paymentDetails->account_type == 0 ? 'Personal' : 'Business' }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->bank_name)
                        <div class="payment-row">
                            <strong>Bank Name:</strong> {{ $paymentDetails->bank_name }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->branch_name)
                        <div class="payment-row">
                            <strong>Branch Name:</strong> {{ $paymentDetails->branch_name }}
                        </div>
                        @endif
                    </div>
                    
                    <div class="payment-column">
                        @if($paymentDetails->account_number)
                        <div class="payment-row">
                            <strong>Account Number:</strong> {{ $paymentDetails->account_number }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->iban)
                        <div class="payment-row">
                            <strong>IBAN:</strong> {{ $paymentDetails->iban }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->swift_code)
                        <div class="payment-row">
                            <strong>SWIFT Code:</strong> {{ $paymentDetails->swift_code }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->ifsc)
                        <div class="payment-row">
                            <strong>IFSC Code:</strong> {{ $paymentDetails->ifsc }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->branch_code)
                        <div class="payment-row">
                            <strong>Branch Code:</strong> {{ $paymentDetails->branch_code }}
                        </div>
                        @endif
                        
                        @if($paymentDetails->recipient_address)
                        <div class="payment-row">
                            <strong>Address:</strong> {{ $paymentDetails->recipient_address }}
                        </div>
                        @endif
                    </div>
                </div>
                
                @if($paymentDetails->additional_notes)
                <div style="margin-top: 15px; padding-top: 10px; border-top: 1px dashed #ddd;">
                    <strong>Notes:</strong> {{ $paymentDetails->additional_notes }}
                </div>
                @endif
            </div>
        @endif

        <div class="footer">
            <p>Thank you for your business! | Invoice generated on {{ date('M j, Y') }}</p>
            <p>For any questions, please contact support</p>
        </div>
    </div>
</body>
</html>