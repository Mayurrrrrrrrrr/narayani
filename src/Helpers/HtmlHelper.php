<?php
declare(strict_types=1);

namespace App\Helpers;

class HtmlHelper
{
    /**
     * Parses active <h2> sections from HTML string into an interactive Table of Contents.
     * Inserts unique id attributes to the <h2> tags and returns modified HTML and TOC array.
     *
     * @param string $html
     * @return array
     */
    public static function generateTOC(string $html): array
    {
        $toc = [];
        $counter = 0;

        // Use preg_replace_callback to find <h2> tags and replace them with an id-injected <h2> tag.
        $modifiedHtml = preg_replace_callback('/<h2([^>]*)>(.*?)<\/h2>/i', function (array $matches) use (&$toc, &$counter) {
            $counter++;
            $attributes = $matches[1];
            $text = strip_tags($matches[2]);
            
            // Create a clean URL-friendly ID slug
            $id = 'toc-heading-' . $counter . '-' . preg_replace('/[^a-z0-9]+/i', '-', strtolower($text));
            $id = trim($id, '-');

            $toc[] = [
                'id' => $id,
                'text' => $text
            ];

            // If there's already an ID, keep it, otherwise inject our generated ID
            if (stripos($attributes, 'id=') !== false) {
                return $matches[0];
            }

            return "<h2 id=\"{$id}\"{$attributes}>{$matches[2]}</h2>";
        }, $html);

        return [
            'html' => $modifiedHtml,
            'toc' => $toc
        ];
    }
}
