<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(CartService $cart): View
    {
        return view('commerce.cart', ['cart' => $cart->summary()]);
    }

    public function store(StoreCartItemRequest $request, CartService $cart): RedirectResponse
    {
        $cart->add((int) $request->validated('product_id'), $request->validated('variant_id') ? (int) $request->validated('variant_id') : null, (int) $request->validated('quantity'));

        return redirect()->route('cart.index')->with('status', 'Produk ditambahkan ke keranjang.');
    }

    public function update(UpdateCartItemRequest $request, CartService $cart): RedirectResponse
    {
        $cart->update((int) $request->validated('product_id'), $request->validated('variant_id') ? (int) $request->validated('variant_id') : null, (int) $request->validated('quantity'));

        return redirect()->route('cart.index')->with('status', 'Keranjang diperbarui.');
    }

    public function destroy(UpdateCartItemRequest $request, CartService $cart): RedirectResponse
    {
        $cart->remove((int) $request->validated('product_id'), $request->validated('variant_id') ? (int) $request->validated('variant_id') : null);

        return redirect()->route('cart.index')->with('status', 'Item dihapus dari keranjang.');
    }

    public function clear(CartService $cart): RedirectResponse
    {
        $cart->clear();

        return redirect()->route('cart.index')->with('status', 'Keranjang dikosongkan.');
    }
}
