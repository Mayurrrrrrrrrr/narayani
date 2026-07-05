<?php
declare(strict_types=1);

namespace App\Helpers;

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed) || str_starts_with($trimmed, '#')) continue;
            
            // Allow lines to not have an '=' gracefully
            if (!str_contains($trimmed, '=')) continue;
            
            [$key, $value] = array_map('trim', explode('=', $trimmed, 2));
            
            // Remove optional quotes surrounding the value
            if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
                $value = substr($value, 1, -1);
            } elseif (str_starts_with($value, "'") && str_ends_with($value, "'")) {
                $value = substr($value, 1, -1);
            }
            
            $_ENV[$key] = $value;
        }
    }

    public static function get(string $key, string $default = ''): string
    {
        return $_ENV[$key] ?? $default;
    }
}
