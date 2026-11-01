<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    public function create(CartService $cart): View|RedirectResponse
    {
        try {
            $summary = $cart->assertCheckoutReady();
        } catch (ValidationException $exception) {
            return redirect()->route('cart.index')->withErrors($exception->errors());
        }

        return view('commerce.checkout', ['cart' => $summary]);
    }

    public function store(StoreCheckoutRequest $request, CartService $cart, CheckoutService $checkout): RedirectResponse
    {
        $summary = $cart->assertCheckoutReady();
        [$order, $token] = $checkout->place($request->validated(), $summary);
        $cart->clear();

        return redirect()->route('orders.show', ['order' => $order->order_number, 'token' => $token])->with('order_access_token', $token);
    }

    public function show(string $order, string $token): Response
    {
        $record = Order::query()->where('order_number', $order)->firstOrFail();
        abort_unless(hash_equals($record->access_token_hash, hash('sha256', $token)), 404);
        $record->load('items');

        return response()->view('commerce.order', ['order' => $record])->header('Cache-Control', 'private, no-store, max-age=0');
    }
}
