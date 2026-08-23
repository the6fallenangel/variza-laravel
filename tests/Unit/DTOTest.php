<?php

declare(strict_types=1);

use The6FallenAngel\VarizaLaravel\DTOs\Expiry;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLink;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLinkRequest;
use The6FallenAngel\VarizaLaravel\DTOs\WebhookPayload;

test('payment link request can be converted to array', function () {
    $request = new PaymentLinkRequest(
        amount: 500000,
        returnUrl: 'https://example.com/callback',
        title: 'Order #123',
        cardLast4: '1234',
        expiresIn: Expiry::OneHour,
    );

    $array = $request->toArray();

    expect($array)->toBe([
        'amount' => 500000,
        'return_url' => 'https://example.com/callback',
        'title' => 'Order #123',
        'card_last_4' => '1234',
        'expires_in' => '1h',
    ]);
});

test('payment link request filters null values', function () {
    $request = new PaymentLinkRequest(
        amount: 500000,
        returnUrl: 'https://example.com/callback',
    );

    $array = $request->toArray();

    expect($array)->toBe([
        'amount' => 500000,
        'return_url' => 'https://example.com/callback',
    ])->not->toHaveKey('title')
        ->not->toHaveKey('card_last_4')
        ->not->toHaveKey('expires_in');
});

test('payment link can be created from array', function () {
    $data = fakePaymentLink();
    $paymentLink = PaymentLink::fromArray($data);

    expect($paymentLink->slug)->toBe('aB3xY9')
        ->and($paymentLink->payUrl)->toBe('https://variza.ir/pay/aB3xY9')
        ->and($paymentLink->amount)->toBe(500000)
        ->and($paymentLink->title)->toBe('Order #123')
        ->and($paymentLink->returnUrl)->toBe('https://example.com/callback')
        ->and($paymentLink->expiresAt)->toBe('2026-08-23T12:00:00+00:00');
});

test('payment link can be created with optional values null', function () {
    $data = [
        'slug' => 'aB3xY9',
        'pay_url' => 'https://variza.ir/pay/aB3xY9',
        'amount' => 500000,
        'return_url' => 'https://example.com/callback',
    ];

    $paymentLink = PaymentLink::fromArray($data);

    expect($paymentLink->title)->toBeNull()
        ->and($paymentLink->expiresAt)->toBeNull();
});

test('expiry enum has correct values', function () {
    expect(Expiry::ThirtyMinutes->value)->toBe('30m')
        ->and(Expiry::OneHour->value)->toBe('1h')
        ->and(Expiry::TwoHours->value)->toBe('2h')
        ->and(Expiry::SixHours->value)->toBe('6h')
        ->and(Expiry::OneDay->value)->toBe('1d')
        ->and(Expiry::ThreeDays->value)->toBe('3d')
        ->and(Expiry::OneWeek->value)->toBe('1w')
        ->and(Expiry::Never->value)->toBe('never');
});

test('webhook payload can be created from array', function () {
    $data = [
        'event' => 'payment.paid',
        'slug' => 'aB3xY9',
        'attempt_code' => '152688947983',
        'amount' => 500128,
        'status' => 'paid',
        'sent_at' => '2026-08-23T11:45:00+00:00',
    ];

    $payload = WebhookPayload::fromArray($data);

    expect($payload->event)->toBe('payment.paid')
        ->and($payload->slug)->toBe('aB3xY9')
        ->and($payload->attemptCode)->toBe('152688947983')
        ->and($payload->amount)->toBe(500128)
        ->and($payload->status)->toBe('paid')
        ->and($payload->sentAt)->toBe('2026-08-23T11:45:00+00:00');
});

test('webhook payload detects payment paid event', function () {
    $payload = new WebhookPayload(
        event: 'payment.paid',
        slug: 'aB3xY9',
        attemptCode: '152688947983',
        amount: 500128,
        status: 'paid',
        sentAt: '2026-08-23T11:45:00+00:00',
    );

    expect($payload->isPaymentPaid())->toBeTrue()
        ->and($payload->isPaid())->toBeTrue();
});

test('webhook payload detects non-paid status', function () {
    $payload = new WebhookPayload(
        event: 'payment.paid',
        slug: 'aB3xY9',
        attemptCode: '152688947983',
        amount: 500128,
        status: 'pending',
        sentAt: '2026-08-23T11:45:00+00:00',
    );

    expect($payload->isPaid())->toBeFalse();
});
