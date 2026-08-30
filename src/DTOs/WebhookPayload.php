<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\DTOs;

final class WebhookPayload
{
    public const EVENT_PAYMENT_PAID = 'payment.paid';

    public const STATUS_PAID = 'paid';

    public function __construct(
        public readonly string $event,
        public readonly string $slug,
        public readonly string $attemptCode,
        public readonly int $amount,
        public readonly string $status,
        public readonly string $sentAt,
        public readonly ?string $memberPhone = null,
    ) {
    }

    public function isPaymentPaid(): bool
    {
        return $this->event === self::EVENT_PAYMENT_PAID;
    }

    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            event: (string) ($data['event'] ?? ''),
            slug: (string) ($data['slug'] ?? ''),
            attemptCode: (string) ($data['attempt_code'] ?? ''),
            amount: (int) ($data['amount'] ?? 0),
            status: (string) ($data['status'] ?? ''),
            sentAt: (string) ($data['sent_at'] ?? ''),
            memberPhone: isset($data['member_phone']) && $data['member_phone'] !== null ? (string) $data['member_phone'] : null,
        );
    }
}
