<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\Facades;

use Illuminate\Support\Facades\Facade;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLink;
use The6FallenAngel\VarizaLaravel\DTOs\PaymentLinkRequest;

/**
 * @method static PaymentLink createPaymentLink(PaymentLinkRequest $request)
 *
 * @see \The6FallenAngel\VarizaLaravel\VarizaClient
 */
final class Variza extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'variza';
    }
}
