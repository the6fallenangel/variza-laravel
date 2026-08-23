<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use The6FallenAngel\VarizaLaravel\DTOs\WebhookPayload;

final class PaymentPaid
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly WebhookPayload $payload,
    ) {
    }
}
