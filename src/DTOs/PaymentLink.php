<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\DTOs;

final class PaymentLink
{
    public function __construct(
        public readonly string $slug,
        public readonly string $payUrl,
        public readonly int $amount,
        public readonly ?string $title,
        public readonly string $returnUrl,
        public readonly ?string $expiresAt,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            slug: (string) ($data['slug'] ?? ''),
            payUrl: (string) ($data['pay_url'] ?? ''),
            amount: (int) ($data['amount'] ?? 0),
            title: isset($data['title']) ? (string) $data['title'] : null,
            returnUrl: (string) ($data['return_url'] ?? ''),
            expiresAt: isset($data['expires_at']) ? (string) $data['expires_at'] : null,
        );
    }
}
