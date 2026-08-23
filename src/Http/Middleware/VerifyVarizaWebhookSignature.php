<?php

declare(strict_types=1);

namespace The6FallenAngel\VarizaLaravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use The6FallenAngel\VarizaLaravel\Exceptions\InvalidSignatureException;

final class VerifyVarizaWebhookSignature
{
    private const SIGNATURE_HEADER = 'X-Webhook-Signature';

    private const SIGNATURE_PREFIX = 'sha256=';

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     *
     * @throws InvalidSignatureException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signature = $request->header(self::SIGNATURE_HEADER);
        $secret = config('variza.webhook_secret');

        if (! $signature || ! $secret) {
            throw new InvalidSignatureException('Missing webhook signature or secret.');
        }

        if (! $this->verify($request->getContent(), $signature, $secret)) {
            throw new InvalidSignatureException();
        }

        return $next($request);
    }

    private function verify(string $payload, string $signatureHeader, string $secret): bool
    {
        $provided = str_starts_with($signatureHeader, self::SIGNATURE_PREFIX)
            ? substr($signatureHeader, strlen(self::SIGNATURE_PREFIX))
            : $signatureHeader;

        $expected = hash_hmac('sha256', $payload, $secret);

        return hash_equals($expected, $provided);
    }
}
