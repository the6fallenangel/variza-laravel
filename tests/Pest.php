<?php

declare(strict_types=1);

use The6FallenAngel\VarizaLaravel\DTOs\Expiry;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLink;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLinkRequest;
use The6FallenAngel\VarizaLaravel\DTOs\WebhookPayload;

function fakePaymentLink(): array
{
    return [
        'slug' => 'aB3xY9',
        'pay_url' => 'https://variza.ir/pay/aB3xY9',
        'amount' => 500000,
        'title' => 'Order #123',
        'return_url' => 'https://example.com/callback',
        'expires_at' => '2026-08-23T12:00:00+00:00',
    ];
}
