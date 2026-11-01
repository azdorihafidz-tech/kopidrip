<?php

namespace App\Http\Controllers;

use App\Services\ManualPaymentWebhookService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManualPaymentWebhookController extends Controller
{
    public function __invoke(Request $request, ManualPaymentWebhookService $service): Response
    {
        $service->handle($request->getContent(), (string) $request->header('X-KopiDrip-Signature'));

        return response()->noContent();
    }
}
