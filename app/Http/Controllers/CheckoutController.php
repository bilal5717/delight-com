<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\OrderItem;
use App\Models\PostDuration;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

     public function showCheckout()
{
    $userId = Auth::id();

    $user = User::where('id', $userId)->first();
    $user_status_id = $user ? $user->user_status_id : null;
    $user_status = $user_status_id ? UserStatus::find($user_status_id) : null;

    $cartItems = Cart::with('post')
        ->where('user_id', $userId)
        ->where('order', 1)
        ->get();

    $durationIds = $cartItems->pluck('duration_id')->unique()->filter()->values();
    $durations = PostDuration::whereIn('id', $durationIds)->get()->keyBy('id');

    // Extract unique location_ids from durations
    $locationIds = $durations->pluck('location_id')->unique()->filter()->values();

    // Fetch shipping addresses corresponding to location_ids
    $durationAddresses = ShippingAddress::with(['city', 'country'])
        ->whereIn('id', $locationIds)
        ->get()
        ->keyBy('id');

    $shippingAddresses = ShippingAddress::with(['city', 'country'])
        ->where('company_id', Auth::user()->company->id)
        ->orderBy('default_address', 'DESC')
        ->get();

    // Static payment methods
    $paymentMethods = collect([
        (object)[
            'id' => 'offlinepayment',
            'name' => 'offlinepayment',
            'display_name' => 'Offline Payment'
        ],
        (object)[
            'id' => 'card',
            'name' => 'card',
            'display_name' => 'Visa/Mastercard'
        ]
    ]);

    return view('account.checkout', compact(
        'cartItems',
        'user_status',
        'durations',
        'paymentMethods',
        'shippingAddresses',
        'durationAddresses' 
    ));
}

    public function complete(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;

        // Validate the request
        $request->validate([
            'payment_method_id' => ['required', Rule::in(['offlinepayment', 'card'])],
            'shipping_address_id' => [
                'required',
                'exists:shipping_address,id',
                Rule::in(ShippingAddress::where('company_id', Auth::user()->company->id)->pluck('id')),
            ],
            'share_location.*' => 'nullable|boolean',
        ]);

        // Log share_location input for debugging
        Log::info('Share Location Input', ['share_location' => $request->input('share_location', [])]);

        // Get cart items
        $cartItems = Cart::where('user_id', $userId)->where('order', 1)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('checkout')->with('error', __('Your cart is empty.'));
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            $addonsTotal = 0;
            $addons = is_string($item->addons) ? json_decode($item->addons, true) : (is_array($item->addons) ? $item->addons : []);
            if (is_array($addons)) {
                $addonsTotal = array_sum(array_column($addons, 'price'));
            }
            return ($item->total_price + $addonsTotal) * $item->quantity;
        });

        $shipping = 5.00;
        $tax = 0.00;
        $totalAmount = $subtotal + $shipping + $tax;

        // Get payment method name
        $paymentMethodName = $request->input('payment_method_id') === 'offlinepayment' ? 'offlinepayment' : 'card';

        try {
            // Create the order
            $order = Order::create([
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_method' => $paymentMethodName,
                'payment_id' => null,
                'shipping_address_id' => $request->input('shipping_address_id'),
            ]);

            // Create order items
            $orderItems = [];
            foreach ($cartItems as $item) {
                $addonsTotal = 0;
                $addons = is_string($item->addons) ? json_decode($item->addons, true) : (is_array($item->addons) ? $item->addons : []);
                if (is_array($addons)) {
                    $addonsTotal = array_sum(array_column($addons, 'price'));
                }

                // Get share_location value for this cart item (default to true since checkbox is checked by default)
                $shareLocation = $request->input("share_location.{$item->id}", true);

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'post_id' => $item->post_id,
                    'duration_id' => $item->duration_id ?? null,
                    'time_slots' => $item->time_slots ?? [],
                    'quantity' => $item->quantity,
                    'base_price' => $item->total_price,
                    'addons' => $addons,
                    'addons_total' => $addonsTotal,
                    'total_price' => ($item->total_price + $addonsTotal) * $item->quantity,
                    'share_location' => (bool) $shareLocation,
                ]);
                $orderItems[] = $orderItem;
            }

            // Update available_units in PostDuration
            foreach ($orderItems as $item) {
                if ($item->duration_id) {
                    $postDuration = PostDuration::find($item->duration_id);
                    if ($postDuration) {
                        $newAvailableUnits = max(0, $postDuration->available_units - $item->quantity);
                        $postDuration->update(['available_units' => $newAvailableUnits]);
                    }
                }
            }

            // Update order
            $order->update([
                'shipping_address_id' => $request->input('shipping_address_id'),
                'payment_method' => $paymentMethodName,
                'status' => 'completed'
            ]);

            // Clear the cart
            Cart::where('user_id', $userId)->where('order', 1)->delete();

            // Redirect to order confirmation
            return redirect()->route('order.confirmation', $order->id)
                            ->with('success', __('Order placed successfully!'));

        } catch (\Exception $e) {
            // Log the error
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'cart_items' => $cartItems,
                'exception' => $e,
            ]);

            // Manually clean up if order or items were created
            if (isset($order)) {
                OrderItem::where('order_id', $order->id)->delete();
                $order->delete();
            }

            // Redirect back with error
            return redirect()->route('checkout')
                            ->with('error', __('Failed to place order. Please try again.'));
        }
    }

   public function confirmation($orderId)
    {
        $order = Order::with(['items.post.pictures', 'items.duration', 'shippingAddress'])->findOrFail($orderId);

        if ((int)$order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Get durations for items
        $durations = $order->items->pluck('duration')->keyBy('id')->filter();
        $locationIds = $durations->pluck('location_id')->unique()->filter()->values();
        // Fetch shipping addresses corresponding to location_ids
    $durationAddresses = ShippingAddress::with(['city', 'country'])
        ->whereIn('id', $locationIds)
        ->get()
        ->keyBy('id');
        return view('account.order-confirmation', compact('order', 'durations','durationAddresses'));
    }
}