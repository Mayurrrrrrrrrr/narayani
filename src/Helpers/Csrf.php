<?php
declare(strict_types=1);

namespace App\Helpers;

class Csrf
{
    public static function generate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validate(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // For API calls, we might read from the X-CSRF-TOKEN header or custom HTTP headers
        $token = $_POST['_csrf'] ?? '';
        if (empty($token)) {
            // Check headers
            $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        }
        if (empty($token) && function_exists('getallheaders')) {
            $headers = getallheaders();
            $token = $headers['X-CSRF-Token'] ?? $headers['X-CSRF-TOKEN'] ?? $headers['x-csrf-token'] ?? '';
        }
        
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::generate()) . '">';
    }
}
