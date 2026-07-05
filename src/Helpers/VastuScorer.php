<?php
declare(strict_types=1);

namespace App\Helpers;

class VastuScorer
{
    /**
     * Analyzes Vastu spatial inputs and calculates compatibility score and customized recommendations.
     *
     * @param array $answers
     * @return array
     */
    public static function score(array $answers): array
    {
        $score = 0;
        $advice = [];

        // 1. Entrance direction
        $entrance = $answers['entrance'] ?? '';
        switch ($entrance) {
            case 'NE':
                $score += 30;
                $advice[] = "North-East entrance is highly auspicious, inviting positive cosmic prana and prosperity flow.";
                break;
            case 'N':
            case 'E':
                $score += 25;
                $advice[] = "North or East entrance is favorable, supporting financial growth and career opportunities.";
                break;
            case 'W':
            case 'S':
                $score += 15;
                $advice[] = "West or South entrance is moderate. Consider adding silver metal dividers or placing heavy green plants near the entrance.";
                break;
            case 'SW':
            default:
                $score += 5;
                $advice[] = "South-West entrance is critical! Nairutya energy clashes. We highly recommend placing a cosmic brass metal corrective strip or Vastu lead pyramids.";
                break;
        }

        // 2. Kitchen direction
        $kitchen = $answers['kitchen'] ?? '';
        switch ($kitchen) {
            case 'SE':
                $score += 25;
                $advice[] = "Kitchen in South-East (Agni zone) is perfectly aligned, inviting health and strong metabolic fire.";
                break;
            case 'NW':
                $score += 20;
                $advice[] = "Kitchen in North-West is supportive, balancing social relations and mental clarity.";
                break;
            case 'NE':
                $score += 5;
                $advice[] = "Kitchen in North-East (Water element zone) clashes severely with Fire. Keep water sources strictly separated from stoves.";
                break;
            default:
                $score += 10;
                $advice[] = "Kitchen placement is moderate. Place a yellow fluorite stone near the cooking station to balance elemental forces.";
                break;
        }

        // 3. Master Bedroom direction
        $bedroom = $answers['bedroom'] ?? '';
        switch ($bedroom) {
            case 'SW':
                $score += 25;
                $advice[] = "Master bedroom in South-West Nairutya zone fosters grounding authority, stable relations, and deep rest.";
                break;
            case 'S':
            case 'W':
                $score += 20;
                $advice[] = "Master bedroom in South/West is favorable, encouraging relaxation and physical restoration.";
                break;
            case 'NE':
            default:
                $score += 5;
                $advice[] = "Bedroom in North-East is problematic for sleep parameters due to high energy currents. Avoid sleeping with your head pointing North.";
                break;
        }

        // 4. Toilet direction
        $toilet = $answers['toilet'] ?? '';
        switch ($toilet) {
            case 'NW':
            case 'W':
                $score += 20;
                $advice[] = "Toilet in North-West is well-placed, channeling waste drainage safely out of energetic zones.";
                break;
            case 'NE':
            case 'SW':
                $score += 5;
                $advice[] = "Toilet in North-East or South-West drains vital cosmic forces. Keep the door closed at all times and place Vastu Himalayan salt bowls inside.";
                break;
            default:
                $score += 12;
                $advice[] = "Toilet placement is moderate. Ensure proper ventilation and avoid storing unused items inside.";
                break;
        }

        // Cap score at 100
        $score = min(100, $score);

        return [
            'score' => $score,
            'advice' => $advice
        ];
    }
}
