<?php
declare(strict_types=1);

namespace App\Helpers;

class RateLimiter
{
    /**
     * Check if the request exceeds rate limits.
     * Returns true if allowed, false if blocked.
     */
    public static function check(string $action, int $maxAttempts, int $decaySeconds): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $now = time();
        $cutoff = $now - $decaySeconds;

        try {
            $db = \App\Services\Database::getConnection();
            
            // Clean up old entries to prevent database pollution
            $cleanStmt = $db->prepare("DELETE FROM `rate_limits` WHERE `timestamp` < ?");
            $cleanStmt->execute([$cutoff]);

            // Count attempts in window
            $countStmt = $db->prepare("SELECT COUNT(*) as cnt FROM `rate_limits` WHERE `ip_address` = ? AND `action` = ? AND `timestamp` >= ?");
            $countStmt->execute([$ip, $action, $cutoff]);
            $attempts = (int)($countStmt->fetch()['cnt'] ?? 0);

            if ($attempts >= $maxAttempts) {
                return false;
            }

            // Log current attempt
            $logStmt = $db->prepare("INSERT INTO `rate_limits` (ip_address, action, timestamp) VALUES (?, ?, ?)");
            $logStmt->execute([$ip, $action, $now]);
            
            return true;
        } catch (\Exception $e) {
            error_log("Rate limiting error: " . $e->getMessage());
            return true; // Graceful fallback
        }
    }
}
