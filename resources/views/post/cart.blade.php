@extends('layouts.master')

@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])

<div class="main-container" style="min-height: calc(100vh - 200px);">
    <div class="floating-notification bg-success" id="notificationContainer"></div>
    <section class="py-5" style="background-color: #eee;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h5 class="mb-3">
                                <a href="{{ url()->previous() }}" class="text-body">
                                    <i class="fas fa-long-arrow-alt-left me-2"></i>{{ t('Back') }}
                                </a>
                            </h5>
                            <hr>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <p class="mb-1">{{ t('shopping_cart') }}</p>
                                    <p class="mb-0">{{ t('your_items_in_cart', ['count' => $cartItems->count()]) }}</p>
                                </div>
                                <div>
                                    <p class="mb-0"><span class="text-muted">{{ t('sort_by') }}:</span> 
                                        <a href="#!" class="text-body">{{ t('price') }} <i class="fas fa-angle-down mt-1"></i></a>
                                    </p>
                                </div>
                            </div>

                            <!-- Cart items as a table -->
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>{{ t('product') }}</th>
                                            <th>{{ t('description') }}</th>
                                            <th>{{ t('price') }}</th>
                                            <th>{{ t('quantity') }}</th>
                                            <th>{{ t('remove') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items">
                                        @foreach ($cartItems as $cartItem)
                                        <?php
                                            if ($cartItem->post->pictures->count() > 0) {
                                                $postImg = imgUrl($cartItem->post->pictures->first()->filename, 'medium');
                                            } else {
                                                $postImg = imgUrl(config('larapen.core.picture.default'), 'medium');
                                            }
                                            $postUrl = \App\Helpers\UrlGen::post($cartItem->post);
                                            $timeSlots = [];
                                            if (!empty($cartItem->time_slots)) {
                                                $timeSlots = is_array($cartItem->time_slots) 
                                                    ? $cartItem->time_slots 
                                                    : json_decode($cartItem->time_slots, true);
                                            }

                                            $duration = isset($durations[$cartItem->duration_id]) ? $durations[$cartItem->duration_id] : null;
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
                                        <tr class="cart-item">
                                            <td class="align-middle"><input type="checkbox" class="remove-checkbox" data-item-id="{{ $cartItem->id }}" onchange="updateOrderStatus(this)" checked></td>
                                            <td class="align-middle">
                                                <img src="{{ $postImg }}" class="img-fluid rounded-3" alt="Shopping item" style="width:100px">
                                            </td>
                                            <td class="align-middle small">
                                                <strong>
                                                    <a href="{{ $postUrl }}" title="{{ $cartItem->post->title }}">
                                                        {{ \Illuminate\Support\Str::limit($cartItem->post->title, 40) }}
                                                    </a>
                                                </strong>
                                                @php
                                                    $addons = $cartItem->addons;
                                                @endphp
                                                @if($addons && is_array($addons))
                                                    <ul>
                                                        @foreach($addons as $addon)
                                                            <li><strong>{{ $addon['addonTitle'] }} - ${{ $addon['price'] }}</strong></li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p>{{ t('no_addons') }}</p>
                                                @endif

                                                <br>
                                                @if($duration)
                                                <div class="mt-2">
                                                    <strong>{{ t('Duration') }}:</strong> <br>
                                                    <span><b>Title:</b>{{ $duration->duration_title }} </span><br>
                                                   <span><b>Days:</b> ({{ $durationDisplay }})</span>
                                                    <br>
                                                    
                                                </div>
                                                @endif
                                                <br>
                                                @if(!empty($timeSlots) && is_array($timeSlots))
                                                <div class="mt-2">
                                                    <strong>{{ t('Time Range') }}:</strong>
                                                    <ul class="list-unstyled time-slots">
                                                        @foreach($timeSlots as $slot)
                                                            @if(is_array($slot) && isset($slot['day']))
                                                                <li>
                                                                    <i class="far fa-clock"></i> 
                                                                    {{ $slot['day'] }}: 
                                                                    {{ $slot['open_time'] ?? '' }} - {{ $slot['close_time'] ?? '' }}
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <h5 class="product-price" data-price="{{ $cartItem->total_price }}">${{ $cartItem->total_price }}</h5>
                                            </td>
                                            <td class="align-middle">
                                                <input type="number" value="{{ $cartItem->quantity }}" class="quantity" 
                                                    style="width: 50px;" min="1" data-item-id="{{ $cartItem->id }}" 
                                                    onblur="updateQuantity(this)">
                                            </td>
                                            <td class="align-middle">
                                                <form action="{{ route('cart.remove', $cartItem->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Remove Selected Button -->
                            <div class="d-flex justify-content-start mt-3 mb-4">
                                <button type="button" class="btn btn-danger" id="remove-selected-btn" onclick="removeSelectedItems()">{{ t('remove_selected') }}</button>
                                <button type="button" class=" mx-1 btn btn-success">
                                    <a href="/" class="text-white">{{ t('continue_shopping') }}</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body p-4">
                            <h3>{{ t('order_summary') }}</h3>
                            <hr class="my-4">

                            <div class="d-flex justify-content-between">
                                <p class="mb-2">{{ t('subtotal') }}</p>
                                <p class="mb-2" id="subtotal">${{ number_format($cartItems->sum('total_price'), 2) }}</p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="mb-2">{{ t('shipping') }}</p>
                                <p class="mb-2" id="shipping">$5.00</p>
                            </div>

                            <div class="d-flex justify-content-between mb-4">
                                <p class="mb-2">{{ t('total_incl_tax') }}</p>
                                <p class="mb-2" id="total">${{ number_format($cartItems->sum('total_price') + 5, 2) }}</p>
                            </div>

                           
                                <!-- Add hidden inputs for selected cart item IDs -->
                                <div id="selected-items"></div>
                                <a href="{{ route('checkout') }}">
                                <button type="submit" class="btn btn-primary w-100 py-2" id="checkout-btn">
                                
                                <div class="d-flex justify-content-center text-white">
                                        <span>{{ t('checkout') }} <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                    </div>
                                </button>
                                </a>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@section('after_style')
<style>
    .product-price {
        font-weight: bold;
    }

    .cart-item {
        margin-bottom: 10px;
    }

    /* Table styles */
    table {
        background-color: #fff;
    }
    table th, table td {
        vertical-align: middle;
    }
   
    .table td, .table th {
        padding: 12px;
        text-align: center;
    }

    /* Centering content vertically */
    .align-middle {
        vertical-align: middle !important;
    }
    
    /* Make table responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Footer spacing */
    .main-container {
        position: relative;
        padding-bottom: 100px; /* Adjust this value based on your footer height */
    }
    
    /* Floating notification */
    .floating-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
        padding: 15px;
        border-radius: 5px;
        color: white;
    }
    
    /* Button spacing */
    .btn-block {
        width: 100%;
    }
    
    /* Disabled button styling */
    #checkout-btn:disabled, #checkout-btn[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
        pointer-events: none;
        background-color: #007bff; /* Maintain Bootstrap primary color */
    }
    
    /* Ensure content doesn't stick to footer */
    body {
        position: relative;
        min-height: 100vh;
    }
</style>
@endsection

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Function to update selected items hidden inputs
function updateSelectedItems() {
    const selectedItemsContainer = document.getElementById('selected-items');
    selectedItemsContainer.innerHTML = ''; // Clear existing inputs
    const checkedItems = document.querySelectorAll('.remove-checkbox:checked');
    
    checkedItems.forEach(item => {
        const itemId = item.getAttribute('data-item-id');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'cart_item_ids[]';
        input.value = itemId;
        selectedItemsContainer.appendChild(input);
    });
}

// Function to update order status
function updateOrderStatus(checkbox) {
    let itemId = checkbox.getAttribute('data-item-id');
    let orderStatus = checkbox.checked ? 1 : 0;

    // Get CSRF token from meta tag
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // AJAX request to update order status
    $.ajax({
        url: '{{ route('cart.updateOrderStatus') }}',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ item_id: itemId, order: orderStatus }),
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function (response) {
            if (response.success) {
                updateCart();
            }
        },
        error: function () {
            // No notification
        }
    });
}

// Function to initialize all items as checked and ordered
function initializeCartItems() {
    const checkboxes = document.querySelectorAll('.remove-checkbox');
    
    checkboxes.forEach(checkbox => {
        // Set checkbox to checked
        checkbox.checked = true;
        
        // Update order status to 1 (true) for each item
        updateOrderStatus(checkbox);
    });
    
    // Update cart totals
    updateCart();
}

// Function to update cart total and checkout button state
function updateCart() {
    let total = 0;
    let itemCount = 0;
    let checkedItems = document.querySelectorAll('.remove-checkbox:checked');
    
    checkedItems.forEach(item => {
        const cartItem = item.closest('.cart-item');
        const quantity = parseInt(cartItem.querySelector('.quantity').value);
        const price = parseFloat(cartItem.querySelector('.product-price').getAttribute('data-price'));
        total += price * quantity;
        itemCount += quantity;
    });

    // Update subtotal and total
    const subtotalElement = document.getElementById('subtotal');
    const shippingElement = document.getElementById('shipping');
    const totalElement = document.getElementById('total');

    if (subtotalElement) {
        subtotalElement.textContent = '$' + total.toFixed(2);
    }
    if (shippingElement) {
        const shipping = itemCount > 0 ? 5 : 0; 
        shippingElement.textContent = '$' + shipping.toFixed(2);
    }
    if (totalElement) {
        const grandTotal = total + (itemCount > 0 ? 5 : 0);
        totalElement.textContent = '$' + grandTotal.toFixed(2);
    }

    // Update selected items hidden inputs
    updateSelectedItems();
}

// Function to remove selected items from cart
function removeSelectedItems() {
    const selectedItems = document.querySelectorAll('.remove-checkbox:checked');
    selectedItems.forEach(item => {
        const cartItem = item.closest('.cart-item');
        const itemId = cartItem.querySelector('.quantity').getAttribute('data-item-id');

        // Get CSRF token from meta tag
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // AJAX request to remove item
        $.ajax({
            url: '{{ route('cart.remove', ['id' => '__ITEM_ID__']) }}'.replace('__ITEM_ID__', itemId),
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function (response) {
                if (response.success) {
                    cartItem.remove();
                    updateCart();
                    showNotification('success', response.message || '{{ t("items removed successfully.") }}');
                } else {
                    showNotification('error', response.message || '{{ t("Failed to remove item.") }}');
                }
            },
            error: function () {
                showNotification('error', '{{ t("An error occurred while removing item.") }}');
            }
        });
    });

    // Update cart total after removal
    updateCart();
}

// Function to update quantity of an item
function updateQuantity(input) {
    let itemId = input.getAttribute('data-item-id');
    let quantity = parseInt(input.value);

    // Ensure quantity is at least 1
    if (quantity < 1) {
        input.value = 1;
        quantity = 1;
    }

    // Get CSRF token from meta tag
    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // AJAX request to update quantity
    $.ajax({
        url: '{{ route('cart.updateQuantity') }}',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ item_id: itemId, quantity: quantity }),
        headers: { 'X-CSRF-TOKEN': csrfToken },
        success: function (response) {
            if (response.success) {
                updateCart();
            } else {
                showNotification('error', response.message || '{{ t("Failed to update quantity.") }}');
            }
        },
        error: function () {
            showNotification('danger', '{{ t("An error occurred while updating quantity.") }}');
        }
    });
}

// Initialize cart on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeCartItems();
});

function showNotification(type, message) {
    let notificationContainer = document.getElementById('notificationContainer');
    notificationContainer.textContent = message;
    notificationContainer.style.display = 'block';
    notificationContainer.className = `floating-notification bg-${type}`;
    
    setTimeout(() => {
        notificationContainer.style.display = 'none';
    }, 5000);
}
</script>
@endsection