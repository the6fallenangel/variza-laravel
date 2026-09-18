<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\DTOs;

final class PaymentLinkRequest
{
    public const RANDOM_CARD = 'random';
    public const VARIZA_CARDS = 'variza';

    public function __construct(
        public readonly int $amount,
        public readonly string $returnUrl,
        public readonly ?string $title = null,
        /**
         * 4 digits of the destination card (e.g. "1234"), self::RANDOM_CARD ("random")
         * for automatic least-load selection (requires RandomLeastLoad plan
         * feature and at least 2 active cards), or self::VARIZA_CARDS ("variza")
         * to receive buyer payments on Variza cards with Toman wallet settlement
         * and USDT withdrawal (requires CustodialSettlement plan feature,
         * no seller bank account needed, max 2,000,000 Toman per link).
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
