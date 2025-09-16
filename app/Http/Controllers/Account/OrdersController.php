<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserStatus;
use App\Models\PostDuration;
use App\Models\CompanyPayment;
use Illuminate\Http\Request as HttpRequest;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Support\Facades\Auth;
use PDF;

class OrdersController extends Controller
{
    protected $pagePath = 'all-orders';

    public function __construct()
    {
        view()->share('pagePath', $this->pagePath);
    }

    public function getOrders(HttpRequest $request)
    {
        MetaTag::set('title', t('Orders'));
        MetaTag::set('description', t('all-orders', ['appName' => config('settings.app.app_name')]));

        // Get order counts and share with view
        $counts = $this->getOrdersCount();
        view()->share('countOrders', $counts['total']);
        view()->share('countPurchasedOrders', $counts['purchased']);
        view()->share('countSalesOrders', $counts['sales']);

        if ($request->ajax()) {
            return response()->json([
                'purchased' => $this->getPurchasedOrdersData(),
                'sales' => $this->getSalesOrdersData(),
                'counts' => $counts
            ]);
        }

        return appView('account.all-orders');
    }

    public function showPayments(HttpRequest $request)
    {
        MetaTag::set('title', t('payments'));
        MetaTag::set('description', t('product-payments', ['appName' => config('settings.app.app_name')]));
        view()->share('pagePath', 'order-payments');
        // Get order counts and share with view
        $counts = $this->getOrdersCount();
        view()->share('countOrders', $counts['total']);
        view()->share('countPurchasedOrders', $counts['purchased']);
        view()->share('countSalesOrders', $counts['sales']);

        if ($request->ajax()) {
            return response()->json([
                'purchased' => $this->getPurchasedOrdersData(),
                'sales' => $this->getSalesOrdersData(),
                'counts' => $counts
            ]);
        }

        return appView('account.order-payments');
    }

    public function getOrdersCount()
    {
        // Count distinct purchased orders
        $purchasedCount = Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->count();

        // Count all sales order items (not distinct orders)
        $salesCount = OrderItem::whereHas('post', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->count();

        return [
            'purchased' => $purchasedCount,
            'sales' => $salesCount,
            'total' => $purchasedCount + $salesCount
        ];
    }

    public function getPurchasedOrders(HttpRequest $request)
    {
        return response()->json(['data' => $this->getPurchasedOrdersData()]);
    }

    public function getSalesOrders(HttpRequest $request)
    {
        return response()->json(['data' => $this->getSalesOrdersData()]);
    }

    public function showOrder($id)
    {
        MetaTag::set('title', t('Order Details'));
        MetaTag::set('description', t('Order Details'));

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with([
                'items.post.user.userStatus',
                'items.duration',
                'shippingAddress',
                'user.userStatus'
            ])
            ->firstOrFail();

        return appView('account.order-details', [
            'order' => $order,
            'type' => 'purchased',
            'isSeller' => false,
            'user_status' => $order->user->userStatus ?? null
        ]);
    }

    public function showSalesOrder($id)
    {
        MetaTag::set('title', t('Order Details'));
        MetaTag::set('description', t('Order Details'));

        $order = Order::where('id', $id)
            ->whereHas('items.post', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with([
                'items.post.user.userStatus',
                'items.duration',
                'shippingAddress',
                'user.userStatus'
            ])
            ->firstOrFail();

        return appView('account.order-details', [
            'order' => $order,
            'type' => 'sales',
            'isSeller' => true,
            'user_status' => $order->items->first()->post->user->userStatus ?? null
        ]);
    }

    protected function getPurchasedOrdersData()
    {
        return Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->with([
                'items.post' => function ($query) {
                    $query->with(['user', 'pictures']);
                },
                'items.duration',
                'shippingAddress'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'created_at' => $order->created_at->toIso8601String(),
                    'status' => $order->status,
                    'total_amount' => $order->total_amount,
                    'subtotal_amount' => $order->subtotal_amount,
                    'tax_amount' => $order->tax_amount,
                    'shipping_amount' => $order->shipping_amount,
                    'items' => $order->items->map(function ($item) {
                        return [
                            'post_title' => $item->post->title ?? 'N/A',
                            'post_url' => \App\Helpers\UrlGen::post($item->post),
                            'duration' => $item->duration ? [
                                'name' => $item->duration->name,
                                'duration_value' => $item->duration->duration_value
                            ] : null,
                            'time_slots_formatted' => $item->time_slots_formatted,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total_price' => $item->total_price,
                            'addons' => $item->addons
                        ];
                    })
                ];
            });
    }

    protected function getSalesOrdersData()
    {
        return OrderItem::whereHas('post', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->with([
                'order.user',
                'post.pictures',
                'duration',
                'order.shippingAddress'
            ])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('order_id')
            ->map(function ($items, $orderId) {
                $firstItem = $items->first();
                return [
                    'order' => [
                        'id' => $orderId,
                        'created_at' => $firstItem->order->created_at->toIso8601String(),
                        'status' => $firstItem->order->status,
                        'user_name' => $firstItem->order->user->name ?? 'N/A',
                        'user_id' => $firstItem->order->user_id,
                        'total_amount' => $firstItem->order->total_amount,
                        'subtotal_amount' => $firstItem->order->subtotal_amount,
                        'tax_amount' => $firstItem->order->tax_amount,
                        'shipping_amount' => $firstItem->order->shipping_amount
                    ],
                    'items' => $items->map(function ($item) {
                        return [
                            'post_title' => $item->post->title ?? 'N/A',
                            'post_url' => \App\Helpers\UrlGen::post($item->post),
                            'duration' => $item->duration ? [
                                'name' => $item->duration->name,
                                'duration_value' => $item->duration->duration_value
                            ] : null,
                            'time_slots_formatted' => $item->time_slots_formatted,
                            'quantity' => $item->quantity,
                            'price' => $item->price,
                            'total_price' => $item->total_price,
                            'addons' => $item->addons
                        ];
                    })
                ];
            })
            ->values();
    }

    public function downloadPurchasedInvoice($orderId)
    {
        $order = Order::with([
            'user',
            'items',
            'items.post',
            'items.post.user',
            'items.post.user.company.payments' => function ($query) {
                $query->where('show_on_invoice', true);
            }
        ])
            ->where('id', $orderId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pdf = PDF::loadView('account.invoice', [
            'order' => $order,
            'type' => 'purchased'
        ]);

        return $pdf->download("invoice-purchased-{$order->id}.pdf");
    }

    public function downloadSalesInvoice($orderId)
    {
        $order = Order::with([
            'user',
            'items',
            'items.post',
            'items.post.user',
            'items.post.user.company.payments' => function ($query) {
                $query->where('show_on_invoice', true);
            }
        ])
            ->where('id', $orderId)
            ->whereHas('items.post', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->firstOrFail();

        $pdf = PDF::loadView('account.invoice', [
            'order' => $order,
            'type' => 'sales'
        ]);

        return $pdf->download("invoice-sales-{$order->id}.pdf");
    }

}