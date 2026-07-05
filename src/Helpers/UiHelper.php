<?php
declare(strict_types=1);

namespace App\Helpers;

class UiHelper
{
    /**
     * Outputs a highly precise SVG star cluster mapping partial ratings.
     *
     * @param float $rating
     * @return string
     */
    public static function renderStars(float $rating): string
    {
        $output = '<div class="flex items-center space-x-1">';
        $uniqueId = uniqid('star-');

        for ($star = 1; $star <= 5; $star++) {
            $diff = $rating - ($star - 1);

            if ($diff >= 1) {
                // Fully filled star
                $fill = '#D4AF37'; // Gold
            } elseif ($diff > 0) {
                // Partially filled star
                $pct = (int)($diff * 100);
                $gradientId = "{$uniqueId}-grad-{$star}";
                
                $output .= "<svg style=\"width:0;height:0;position:absolute;\" aria-hidden=\"true\">
                    <defs>
                        <linearGradient id=\"{$gradientId}\" x1=\"0%\" y1=\"0%\" x2=\"100%\" y2=\"0%\">
                            <stop offset=\"{$pct}%\" stop-color=\"#D4AF37\" />
                            <stop offset=\"{$pct}%\" stop-color=\"#E2E8F0\" />
                        </linearGradient>
                    </defs>
                </svg>";
                
                $fill = "url(#{$gradientId})";
            } else {
                // Empty star
                $fill = '#E2E8F0'; // Gray
            }

            $output .= "<svg class=\"w-5 h-5\" fill=\"{$fill}\" viewBox=\"0 0 24 24\" stroke=\"none\">
                <path d=\"M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z\" />
            </svg>";
        }

        $output .= '</div>';
        return $output;
    }
}
