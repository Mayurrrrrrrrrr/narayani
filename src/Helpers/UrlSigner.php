<?php
declare(strict_types=1);

namespace App\Helpers;

class UrlSigner
{
    private static function getSecret(): string
    {
        return Env::get('APP_KEY', 'default_secret_key_12893891283');
    }

    public static function generateSignedUrl(int $bookingId, int $ttl = 3600): string
    {
        $expires = time() + $ttl;
        $secret = self::getSecret();
        $signature = hash_hmac('sha256', "booking_id={$bookingId}&expires={$expires}", $secret);
        return "/booking/report/download?booking_id={$bookingId}&expires={$expires}&signature={$signature}";
    }

    public static function validate(int $bookingId, int $expires, string $signature): bool
    {
        if (time() > $expires) {
            return false;
        }
        $secret = self::getSecret();
        $expected = hash_hmac('sha256', "booking_id={$bookingId}&expires={$expires}", $secret);
        return hash_equals($expected, $signature);
    }
}
