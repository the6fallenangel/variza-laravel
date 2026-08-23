<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use The6FallenAngel\VarizaLaravel\DTOs\WebhookPayload;
use The6FallenAngel\VarizaLaravel\Events\PaymentPaid;

final class WebhookController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $payload = WebhookPayload::fromArray($request->all());

        if ($payload->isPaymentPaid()) {
            event(new PaymentPaid($payload));
        }

        return response()->json(['status' => 'ok']);
    }
}
