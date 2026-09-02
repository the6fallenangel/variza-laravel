<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\DTOs;

final class PaymentLinkRequest
{
    public const RANDOM_CARD = 'random';

    public function __construct(
        public readonly int $amount,
        public readonly string $returnUrl,
        public readonly ?string $title = null,
        /**
         * 4 digits of the destination card (e.g. "1234") or self::RANDOM_CARD ("random")
         * for automatic least-load selection. Requires plan with RandomLeastLoad feature
         * and at least 2 active cards on the seller account.
         */
        public readonly ?string $cardLast4 = null,
        public readonly ?Expiry $expiresIn = null,
        public readonly ?string $memberPhone = null,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'amount' => $this->amount,
            'return_url' => $this->returnUrl,
            'title' => $this->title,
            'card_last_4' => $this->cardLast4,
            'expires_in' => $this->expiresIn?->value,
            'member_phone' => $this->memberPhone,
        ], static fn (mixed $value): bool => $value !== null);
    }
}
