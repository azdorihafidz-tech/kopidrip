<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $request->validated();

        return to_route('contact')->with(
            'contact-status',
            'Validasi formulir berhasil. Pengiriman dan penyimpanan pesan belum diaktifkan.',
        );
    }
}
