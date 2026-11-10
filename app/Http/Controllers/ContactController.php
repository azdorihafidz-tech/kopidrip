<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::query()->create($request->validated());

        return to_route('contact')->with(
            'contact-status',
            'Pesan berhasil dikirim. Tim Kopi Drip akan menindaklanjutinya.',
        );
    }
}
