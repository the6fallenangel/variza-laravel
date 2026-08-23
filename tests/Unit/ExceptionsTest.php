<?php

declare(strict_types=1);

test('exceptions can be instantiated', function () {
    $exception = new \The6FallenAngel\VarizaLaravel\Exceptions\VarizaException('Test error');
    
    expect($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\VarizaException::class);
});

test('api exception extends variza exception', function () {
    $exception = new \The6FallenAngel\VarizaLaravel\Exceptions\ApiException(status: 422);
    
    expect($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\ApiException::class)
        ->and($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\VarizaException::class);
});

test('validation exception extends api exception', function () {
    $exception = new \The6FallenAngel\VarizaLaravel\Exceptions\ValidationException(status: 422);
    
    expect($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\ValidationException::class)
        ->and($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\ApiException::class);
});

test('rate limit exception extends api exception', function () {
    $exception = new \The6FallenAngel\VarizaLaravel\Exceptions\RateLimitException(status: 429);
    
    expect($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\RateLimitException::class)
        ->and($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\ApiException::class);
});

test('invalid signature exception extends variza exception', function () {
    $exception = new \The6FallenAngel\VarizaLaravel\Exceptions\InvalidSignatureException();
    
    expect($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\InvalidSignatureException::class)
        ->and($exception)->toBeInstanceOf(\The6FallenAngel\VarizaLaravel\Exceptions\VarizaException::class);
});
