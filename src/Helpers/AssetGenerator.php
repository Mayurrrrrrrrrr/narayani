<?php
declare(strict_types=1);

namespace App\Helpers;

class AssetGenerator
{
    /**
     * Render a premium Sacred Lotus Logo SVG with cool-to-warm gradients and gold lines.
     */
    public static function logo(int $size = 500): string
    {
        $center = $size / 2;
        $r = $size * 0.22;

        ob_start();
        echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        ?>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?= $size ?> <?= $size ?>" width="100%" height="100%">
            <defs>
                <!-- Cool-to-warm premium gradient -->
                <linearGradient id="lotusGrad" x1="0%" y1="100%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#7B2FF7" />
                    <stop offset="40%" stop-color="#FF2E9A" />
                    <stop offset="100%" stop-color="#D4AF37" />
                </linearGradient>
                <!-- Gold glow filter -->
                <filter id="goldGlow" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="3" result="blur" />
                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                </filter>
            </defs>
            <style>
                .lotus-bg { fill: url(#lotusGrad); opacity: 0.85; }
                .lotus-line { stroke: #D4AF37; stroke-width: 1.5; fill: none; filter: url(#goldGlow); }
                .outer-ring { stroke: #D4AF37; stroke-width: 1; fill: none; stroke-dasharray: 4 4; opacity: 0.6; }
            </style>

            <!-- Concentric sacred outer rings -->
            <circle cx="<?= $center ?>" cy="<?= $center ?>" r="<?= $size * 0.42 ?>" class="outer-ring" />
            <circle cx="<?= $center ?>" cy="<?= $center ?>" r="<?= $size * 0.38 ?>" class="outer-ring" style="stroke-dasharray: none; opacity: 0.25;" />

            <!-- Decorative boundary dots -->
            <?php
            for ($i = 0; $i < 24; $i++) {
                $angle = ($i * (360 / 24)) * M_PI / 180;
                $dx = $center + ($size * 0.42) * cos($angle);
                $dy = $center + ($size * 0.42) * sin($angle);
                echo "<circle cx=\"{$dx}\" cy=\"{$dy}\" r=\"2.5\" fill=\"#D4AF37\" opacity=\"0.8\" />";
            }
            ?>

            <!-- Lotus Geometry (Overlapping petals) -->
            <g transform="translate(<?= $center ?>, <?= $center + $r * 0.15 ?>)">
                <?php
                // 12 overlapping petals rotated around the center
                $petalCount = 12;
                for ($i = 0; $i < $petalCount; $i++) {
                    $angle = ($i * (360 / $petalCount));
                    // Construct petal paths using quadratic curves
                    ?>
                    <g transform="rotate(<?= $angle ?>)">
                        <path d="M 0 0 C <?= -$r * 0.5 ?> <?= -$r * 0.8 ?>, <?= -$r * 0.2 ?> <?= -$r * 1.5 ?>, 0 <?= -$r * 1.8 ?> C <?= $r * 0.2 ?> <?= -$r * 1.5 ?>, <?= $r * 0.5 ?> <?= -$r * 0.8 ?>, 0 0" class="lotus-bg" />
                        <path d="M 0 0 C <?= -$r * 0.5 ?> <?= -$r * 0.8 ?>, <?= -$r * 0.2 ?> <?= -$r * 1.5 ?>, 0 <?= -$r * 1.8 ?> C <?= $r * 0.2 ?> <?= -$r * 1.5 ?>, <?= $r * 0.5 ?> <?= -$r * 0.8 ?>, 0 0" class="lotus-line" />
                    </g>
                    <?php
                }
                ?>
                <!-- Golden Center Seed (Bindu) -->
                <circle cx="0" cy="0" r="12" fill="#D4AF37" filter="url(#goldGlow)" />
                <circle cx="0" cy="0" r="22" stroke="#D4AF37" stroke-width="1.2" fill="none" opacity="0.6" />
            </g>
        </svg>
        <?php
        return ob_get_clean();
    }

    /**
     * Render a seed-based mathematical sacred geometry SVG.
     */
    public static function geometry(string $seed = 'narayani', int $size = 500): string
    {
        // Deterministic seeding based on input string
        $numSeed = crc32($seed);
        mt_srand($numSeed);

        $center = $size / 2;
        $type = mt_rand(0, 2); // 0: Cosmic Starfield, 1: Flower of Life segment, 2: Seed-based Sri Yantra variant

        ob_start();
        echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        ?>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?= $size ?> <?= $size ?>" width="100%" height="100%">
            <style>
                .geo-path { stroke: #D4AF37; stroke-width: 1.2; fill: none; opacity: 0.8; }
                .geo-accent { stroke: #FF2E9A; stroke-width: 1; fill: none; opacity: 0.6; }
                .geo-node { fill: #7B2FF7; opacity: 0.9; }
                .geo-glow { stroke: #D4AF37; stroke-width: 2.5; fill: none; opacity: 0.2; filter: blur(1px); }
            </style>

            <?php if ($type === 0): ?>
                <!-- Cosmic Constellation Starfield -->
                <rect width="100%" height="100%" fill="#07060B" />
                <?php
                $nodeCount = mt_rand(15, 30);
                $nodes = [];
                for ($i = 0; $i < $nodeCount; $i++) {
                    $nodes[] = [
                        'x' => mt_rand((int)($size * 0.1), (int)($size * 0.9)),
                        'y' => mt_rand((int)($size * 0.1), (int)($size * 0.9)),
                        'r' => mt_rand(2, 6)
                    ];
                }

                // Draw constellation lines between close nodes
                for ($i = 0; $i < $nodeCount; $i++) {
                    for ($j = $i + 1; $j < $nodeCount; $j++) {
                        $dist = sqrt(pow($nodes[$i]['x'] - $nodes[$j]['x'], 2) + pow($nodes[$i]['y'] - $nodes[$j]['y'], 2));
                        if ($dist < $size * 0.28) {
                            $opacity = 1.0 - ($dist / ($size * 0.28));
                            $color = mt_rand(0, 1) === 1 ? '#D4AF37' : '#FF2E9A';
                            echo "<line x1=\"{$nodes[$i]['x']}\" y1=\"{$nodes[$i]['y']}\" x2=\"{$nodes[$j]['x']}\" y2=\"{$nodes[$j]['y']}\" stroke=\"{$color}\" stroke-width=\"0.8\" opacity=\"{$opacity}\" />";
                        }
                    }
                }

                // Draw node stars
                foreach ($nodes as $node) {
                    $glowColor = mt_rand(0, 1) === 1 ? '#7B2FF7' : '#2E7CF6';
                    echo "<circle cx=\"{$node['x']}\" cy=\"{$node['y']}\" r=\"{$node['r']}\" fill=\"{$glowColor}\" />";
                    echo "<circle cx=\"{$node['x']}\" cy=\"{$node['y']}\" r=\"{$node['r']}\" stroke=\"#F5F3FA\" stroke-width=\"0.5\" fill=\"none\" />";
                }
                ?>

            <?php elseif ($type === 1): ?>
                <!-- Sacred Flower of Life Matrix Segment -->
                <circle cx="<?= $center ?>" cy="<?= $center ?>" r="<?= $size * 0.44 ?>" class="geo-path" />
                <?php
                $r = $size * 0.12;
                // Generate a grid of intersecting circles
                for ($row = -3; $row <= 3; $row++) {
                    for ($col = -3; $col <= 3; $col++) {
                        // Offset rows
                        $cx = $center + $col * $r * 1.5;
                        $cy = $center + $row * $r * sqrt(3) + (($col % 2 === 0) ? 0 : $r * sqrt(3) / 2);
                        
                        // Check if within bounds
                        $dist = sqrt(pow($cx - $center, 2) + pow($cy - $center, 2));
                        if ($dist <= $size * 0.38) {
                            echo "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$r}\" class=\"geo-path\" />";
                            echo "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$r}\" class=\"geo-glow\" />";
                        }
                    }
                }
                ?>

            <?php else: ?>
                <!-- Seed-based Concentric Golden Mandala Yantra -->
                <circle cx="<?= $center ?>" cy="<?= $center ?>" r="<?= $size * 0.45 ?>" class="geo-path" />
                <circle cx="<?= $center ?>" cy="<?= $center ?>" r="<?= $size * 0.45 ?>" class="geo-glow" />
                
                <?php
                $layers = mt_rand(4, 7);
                for ($l = 1; $l <= $layers; $l++) {
                    $radius = ($size * 0.45) * ($l / $layers);
                    $petals = mt_rand(6, 16);
                    echo "<circle cx=\"{$center}\" cy=\"{$center}\" r=\"{$radius}\" class=\"geo-path\" style=\"stroke-dasharray: " . mt_rand(2, 6) . " " . mt_rand(2, 6) . ";\" />";
                    
                    for ($p = 0; $p < $petals; $p++) {
                        $angle = ($p * (360 / $petals)) * M_PI / 180;
                        $px = $center + $radius * cos($angle);
                        $py = $center + $radius * sin($angle);
                        $petalSize = $radius * 0.25;
                        echo "<circle cx=\"{$px}\" cy=\"{$py}\" r=\"{$petalSize}\" class=\"geo-accent\" />";
                    }
                }
                ?>
                <circle cx="<?= $center ?>" cy="<?= $center ?>" r="8" fill="#D4AF37" />
            <?php endif; ?>
        </svg>
        <?php
        return ob_get_clean();
    }

    /**
     * Renders a solid aspect-ratio placeholder container with customizable dimensions and text.
     */
    public static function placeholder(int $width = 600, int $height = 400, string $text = 'Layout Placeholder', string $bgColor = '#161420', string $textColor = '#F5F3FA'): string
    {
        $bg = htmlspecialchars($bgColor, ENT_QUOTES, 'UTF-8');
        $textCol = htmlspecialchars($textColor, ENT_QUOTES, 'UTF-8');
        $textSafe = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

        ob_start();
        echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        ?>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?= $width ?> <?= $height ?>" width="100%" height="100%">
            <rect width="100%" height="100%" fill="<?= $bg ?>" />
            <!-- Design Grid overlay -->
            <line x1="0" y1="0" x2="<?= $width ?>" y2="<?= $height ?>" stroke="<?= $textCol ?>" stroke-width="0.5" opacity="0.1" />
            <line x1="<?= $width ?>" y1="0" x2="0" y2="<?= $height ?>" stroke="<?= $textCol ?>" stroke-width="0.5" opacity="0.1" />
            <rect x="20" y="20" width="<?= $width - 40 ?>" height="<?= $height - 40 ?>" fill="none" stroke="<?= $textCol ?>" stroke-width="1" opacity="0.15" />
            
            <!-- Sacred corner motifs -->
            <circle cx="20" cy="20" r="8" fill="none" stroke="<?= $textCol ?>" stroke-width="1" opacity="0.3" />
            <circle cx="<?= $width - 20 ?>" cy="20" r="8" fill="none" stroke="<?= $textCol ?>" stroke-width="1" opacity="0.3" />
            <circle cx="20" cy="<?= $height - 20 ?>" r="8" fill="none" stroke="<?= $textCol ?>" stroke-width="1" opacity="0.3" />
            <circle cx="<?= $width - 20 ?>" cy="<?= $height - 20 ?>" r="8" fill="none" stroke="<?= $textCol ?>" stroke-width="1" opacity="0.3" />
            
            <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="'Manrope', sans-serif" font-size="18" font-weight="600" fill="<?= $textCol ?>" letter-spacing="2" opacity="0.9">
                <?= $textSafe ?>
            </text>
            <text x="50%" y="58%" dominant-baseline="middle" text-anchor="middle" font-family="'Cormorant Garamond', serif" font-size="12" font-style="italic" fill="<?= $textCol ?>" opacity="0.5">
                Narayani Dynamic Asset
            </text>
        </svg>
        <?php
        return ob_get_clean();
    }
}
