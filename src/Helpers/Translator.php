<?php
declare(strict_types=1);

namespace App\Helpers {

    class Translator
    {
        private static ?array $translations = null;
        private static ?string $loadedLocale = null;

        /**
         * Get active locale from session or fallback to default 'en'.
         */
        public static function getLocale(): string
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            return $_SESSION['locale'] ?? 'en';
        }

        /**
         * Set active locale in session.
         */
        public static function setLocale(string $locale): void
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (in_array($locale, ['en', 'hi'], true)) {
                $_SESSION['locale'] = $locale;
            }
            self::$translations = null; // Reset cached translations
            self::$loadedLocale = null;
        }

        /**
         * Translate key using lang dictionaries.
         */
        public static function translate(string $key, array $replacements = []): string
        {
            $locale = self::getLocale();
            
            // Cache loaded dictionary
            if (self::$translations === null || self::$loadedLocale !== $locale) {
                self::$loadedLocale = $locale;
                $file = dirname(__DIR__, 2) . "/lang/{$locale}.php";
                if (file_exists($file)) {
                    self::$translations = require $file;
                } else {
                    self::$translations = [];
                }
            }

            $translation = self::$translations[$key] ?? null;

            // Fallback to English if Hindi key is missing
            if ($translation === null && $locale === 'hi') {
                $enFile = dirname(__DIR__, 2) . "/lang/en.php";
                if (file_exists($enFile)) {
                    $enDict = require $enFile;
                    $translation = $enDict[$key] ?? null;
                }
            }

            if ($translation === null) {
                return $key;
            }

            // Apply replacements
            foreach ($replacements as $search => $replace) {
                $translation = str_replace('{' . $search . '}', (string)$replace, $translation);
            }

            return $translation;
        }

        /**
         * Fetch localized database field with fallback.
         */
        public static function dbField($row, string $baseField): string
        {
            $locale = self::getLocale();
            
            if (is_array($row)) {
                $fieldHi = "{$baseField}_hi";
                $fieldEn = "{$baseField}_en";

                if ($locale === 'hi' && isset($row[$fieldHi]) && !empty($row[$fieldHi])) {
                    return (string)$row[$fieldHi];
                }
                if (isset($row[$fieldEn]) && !empty($row[$fieldEn])) {
                    return (string)$row[$fieldEn];
                }
                if (isset($row[$baseField])) {
                    return (string)$row[$baseField];
                }
            } elseif (is_object($row)) {
                $fieldHi = "{$baseField}_hi";
                $fieldEn = "{$baseField}_en";

                if ($locale === 'hi' && isset($row->$fieldHi) && !empty($row->$fieldHi)) {
                    return (string)$row->$fieldHi;
                }
                if (isset($row->$fieldEn) && !empty($row->$fieldEn)) {
                    return (string)$row->$fieldEn;
                }
                if (isset($row->$baseField)) {
                    return (string)$row->$baseField;
                }
            }

            return '';
        }
    }
}

// Global shortcut helper functions in root namespace
namespace {
    if (!function_exists('t')) {
        function t(string $key, array $replacements = []): string
        {
            return \App\Helpers\Translator::translate($key, $replacements);
        }
    }

    if (!function_exists('db_trans')) {
        function db_trans($row, string $baseField): string
        {
            return \App\Helpers\Translator::dbField($row, $baseField);
        }
    }
}
