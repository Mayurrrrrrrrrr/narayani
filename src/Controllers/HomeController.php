<?php
declare(strict_types=1);

namespace App\Controllers;

class HomeController extends BaseController
{
    public function index(): void
    {
        $consultant = null;
        $categories = [];
        $testimonials = [];

        try {
            $db = \App\Services\Database::getConnection();

            // Fetch primary consultant profile
            $consultantStmt = $db->query("SELECT * FROM `consultant_profile` LIMIT 1");
            $consultant = $consultantStmt->fetch() ?: null;

            // Fetch active service categories
            $categoriesStmt = $db->query("SELECT * FROM `service_categories` LIMIT 6");
            $categories = $categoriesStmt->fetchAll() ?: [];

            // Fetch approved testimonials
            $testimonialsStmt = $db->query("SELECT * FROM `testimonials` WHERE `is_approved` = 1 LIMIT 10");
            $testimonials = $testimonialsStmt->fetchAll() ?: [];

        } catch (\Exception $e) {
            // Log or ignore database issues to ensure high desktop performance / fallback compatibility
            error_log("Database error in HomeController@index: " . $e->getMessage());
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Narayani Portal',
            'url' => 'https://narayani.yuktaa.com',
            'logo' => 'https://narayani.yuktaa.com/generate-asset?type=geometry&seed=narayani-logo&w=300&h=300',
            'description' => 'Experience wellness, sacred motifs, and pure transformation at the Narayani Portal.'
        ];

        $this->render('pages/home', [
            'title' => 'Home - Narayani Portal',
            'meta_description' => 'Experience wellness, sacred motifs, and pure transformation at the Narayani Portal.',
            'consultant' => $consultant,
            'categories' => $categories,
            'testimonials' => $testimonials,
            'schema_json' => json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        ]);
    }

    public function services(): void
    {
        $categories = [];
        try {
            $db = \App\Services\Database::getConnection();
            $stmt = $db->query("
                SELECT c.*, COUNT(s.id) as service_count 
                FROM `service_categories` c 
                LEFT JOIN `services` s ON c.id = s.category_id AND s.is_active = 1 
                GROUP BY c.id
            ");
            $categories = $stmt->fetchAll() ?: [];
        } catch (\Exception $e) {
            error_log("Database error in HomeController@services: " . $e->getMessage());
        }

        $this->render('pages/services', [
            'title' => 'Our Services - Narayani Portal',
            'meta_description' => 'Explore the custom Vastu, Jyotish, and Spiritual Healing alignments.',
            'categories' => $categories,
        ]);
    }

    public function serviceDetail(string $slug): void
    {
        try {
            $db = \App\Services\Database::getConnection();
            
            // 1. Try to fetch as category
            $catStmt = $db->prepare("SELECT * FROM `service_categories` WHERE `slug` = ? LIMIT 1");
            $catStmt->execute([$slug]);
            $category = $catStmt->fetch() ?: null;

            if ($category !== null) {
                // Fetch services for this category
                $servicesStmt = $db->prepare("SELECT * FROM `services` WHERE `category_id` = ? AND `is_active` = 1");
                $servicesStmt->execute([$category['id']]);
                $services = $servicesStmt->fetchAll() ?: [];

                $this->render('pages/category_detail', [
                    'title' => "{$category['name_en']} - Narayani Portal",
                    'meta_description' => "Explore our offerings in {$category['name_en']}.",
                    'category' => $category,
                    'services' => $services,
                ]);
                return;
            }

            // 2. Try to fetch as individual service
            $serviceStmt = $db->prepare("SELECT * FROM `services` WHERE `slug` = ? AND `is_active` = 1 LIMIT 1");
            $serviceStmt->execute([$slug]);
            $service = $serviceStmt->fetch() ?: null;

            if ($service !== null) {
                // Fetch category details
                $catStmt = $db->prepare("SELECT * FROM `service_categories` WHERE `id` = ? LIMIT 1");
                $catStmt->execute([$service['category_id']]);
                $category = $catStmt->fetch() ?: null;

                // Fetch up to 3 related active services in the same category (excluding this one)
                $relatedStmt = $db->prepare("SELECT * FROM `services` WHERE `category_id` = ? AND `id` != ? AND `is_active` = 1 LIMIT 3");
                $relatedStmt->execute([$service['category_id'], $service['id']]);
                $relatedServices = $relatedStmt->fetchAll() ?: [];

                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => $service['title'],
                    'description' => $service['short_desc'] ?? '',
                    'provider' => [
                        '@type' => 'Person',
                        'name' => 'Acharya Narayani Devi'
                    ],
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $service['price_inr'],
                        'priceCurrency' => 'INR'
                    ]
                ];

                $this->render('pages/service_detail', [
                    'title' => "{$service['title']} - Narayani Portal",
                    'meta_description' => htmlspecialchars($service['short_desc'] ?? ''),
                    'service' => $service,
                    'category' => $category,
                    'relatedServices' => $relatedServices,
                    'schema_json' => json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
                ]);
                return;
            }

            // 3. Fallback to 404
            $this->notFound();

        } catch (\Exception $e) {
            error_log("Database error in HomeController@serviceDetail: " . $e->getMessage());
            $this->notFound();
        }
    }

    public function about(): void
    {
        $consultant = null;
        try {
            $db = \App\Services\Database::getConnection();
            $stmt = $db->query("SELECT * FROM `consultant_profile` LIMIT 1");
            $consultant = $stmt->fetch() ?: null;
        } catch (\Exception $e) {
            error_log("Database error in HomeController@about: " . $e->getMessage());
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => 'Acharya Narayani Devi',
            'jobTitle' => 'Vastu & Astrological Consultant',
            'worksFor' => [
                '@type' => 'Organization',
                'name' => 'Narayani Portal'
            ],
            'description' => 'Acharya Narayani Devi has over 15 years of experience in Vedic Vastu auditing, astrological alignments, and cosmological geometry consultations.'
        ];

        $this->render('pages/about', [
            'title' => 'About Us - Narayani Portal',
            'meta_description' => 'Our journey, mission, and the ancient wisdom behind Narayani Portal.',
            'consultant' => $consultant,
            'schema_json' => json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
        ]);
    }

    public function contact(): void
    {
        $this->render('pages/contact', [
            'title' => 'Contact Us - Narayani Portal',
            'meta_description' => 'Get in touch with the Narayani Portal group for bookings and enquiries.',
        ]);
    }

    public function dashboard(): void
    {
        $this->render('pages/dashboard', [
            'title' => 'Customer Dashboard - Narayani Portal',
            'meta_description' => 'Access your booking options, payment history, and personalized schedule.',
        ]);
    }

    public function booking(): void
    {
        $services = [];
        $consultant = null;
        try {
            $db = \App\Services\Database::getConnection();
            $stmt = $db->query("
                SELECT s.*, c.slug as category_slug 
                FROM `services` s 
                JOIN `service_categories` c ON s.category_id = c.id 
                WHERE s.is_active = 1
            ");
            $services = $stmt->fetchAll() ?: [];

            $cStmt = $db->query("SELECT * FROM `consultant_profile` LIMIT 1");
            $consultant = $cStmt->fetch() ?: null;
        } catch (\Exception $e) {
            error_log("Database error in HomeController@booking: " . $e->getMessage());
        }

        $this->render('pages/booking', [
            'title' => 'Book a Consultation - Narayani Portal',
            'meta_description' => 'Schedule a sacred alignment session with Acharya Vinay Dev.',
            'services' => $services,
            'consultant' => $consultant,
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('pages/404', [
            'title' => 'Page Not Found - Narayani Portal',
            'meta_description' => 'The page you are looking for does not exist.',
        ]);
    }

    public function designSystem(): void
    {
        $this->render('pages/design_system', [
            'title' => 'Design System - Narayani Portal',
            'meta_description' => 'Preview and visual guidelines of all programmatic UI components and design system tokens.',
        ]);
    }

    public function vector(): void
    {
        $type = $_GET['type'] ?? 'sri-yantra';
        $color = $_GET['color'] ?? '#D4AF37';
        $width = isset($_GET['width']) ? (int)$_GET['width'] : 500;
        $height = isset($_GET['height']) ? (int)$_GET['height'] : 400;
        $text = $_GET['text'] ?? 'Layout Placeholder';

        header('Content-Type: image/svg+xml');
        header('Cache-Control: public, max-age=86400');

        if ($type === 'mandala') {
            echo \App\Helpers\AssetGenerator::mandala($width, 12, $color);
        } elseif ($type === 'placeholder') {
            echo \App\Helpers\AssetGenerator::placeholder($width, $height, $text, '#161420', $color);
        } else {
            echo \App\Helpers\AssetGenerator::sriYantra($width, $color);
        }
        exit;
    }

    public function generateAsset(): void
    {
        $type = $_GET['type'] ?? 'logo';
        $seed = $_GET['seed'] ?? 'narayani';
        $w = isset($_GET['w']) ? (int)$_GET['w'] : (isset($_GET['width']) ? (int)$_GET['width'] : 500);
        $h = isset($_GET['h']) ? (int)$_GET['h'] : (isset($_GET['height']) ? (int)$_GET['height'] : 400);
        $text = $_GET['text'] ?? 'Layout Placeholder';
        $color = $_GET['color'] ?? '#D4AF37';

        header('Content-Type: image/svg+xml');
        header('Cache-Control: public, max-age=86400');

        if ($type === 'logo') {
            echo \App\Helpers\AssetGenerator::logo($w);
        } elseif ($type === 'geometry') {
            echo \App\Helpers\AssetGenerator::geometry($seed, $w);
        } else {
            echo \App\Helpers\AssetGenerator::placeholder($w, $h, $text, '#161420', $color);
        }
        exit;
    }

    public function privacyPolicy(): void
    {
        $this->render('pages/privacy_policy', [
            'title' => 'Privacy Policy - Narayani Portal',
            'meta_description' => 'Legal framework and privacy policy guidelines for Narayani Portal.',
        ]);
    }

    public function termsConditions(): void
    {
        $this->render('pages/terms_conditions', [
            'title' => 'Terms & Conditions - Narayani Portal',
            'meta_description' => 'Usage terms and sacred consultation agreements for Narayani Portal.',
        ]);
    }

    public function refundPolicy(): void
    {
        $this->render('pages/refund_policy', [
            'title' => 'Cancellation & Refund Policy - Narayani Portal',
            'meta_description' => 'Refund structures and cancellation window details for Narayani Portal.',
        ]);
    }

    /**
     * POST /contact
     */
    public function submitContact(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }

        if (!\App\Helpers\RateLimiter::check('contact_submission', 10, 3600)) {
            $_SESSION['contact_error'] = 'Too many contact submissions. Please try again in an hour.';
            header('Location: /contact');
            exit;
        }
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($name) || empty($message) || (empty($email) && empty($phone))) {
            $_SESSION['contact_error'] = 'Name, message, and contact coordinates (email or phone) are required.';
            header('Location: /contact');
            exit;
        }

        try {
            $db = \App\Services\Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO `leads` (name, email, phone, source, message) 
                VALUES (?, ?, ?, 'Contact Form', ?)
            ");
            $stmt->execute([$name, $email, $phone, $message]);

            // Trigger admin notification log
            $logDir = dirname(__DIR__, 2) . '/storage/logs';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0775, true);
            }
            $logFile = $logDir . '/mail.log';
            $logEntry = "[" . date('Y-m-d H:i:s') . "] TO: admin@narayani.com | SUBJECT: New Lead Alert: {$name} | MESSAGE: {$message}\n";
            error_log($logEntry, 3, $logFile);

            $_SESSION['contact_success'] = 'Your message has been successfully alignment-registered. Acharya Vinay Dev will connect with you.';

        } catch (\Exception $e) {
            error_log("Database error in HomeController@submitContact: " . $e->getMessage());
            $_SESSION['contact_error'] = 'A system error occurred. Please try again.';
        }

        header('Location: /contact');
        exit;
    }

    /**
     * POST /api/log-lead
     */
    public function logLead(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        // Handle JSON or standard form POST inputs
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $name = $input['name'] ?? 'Anonymous Interaction';
        $email = $input['email'] ?? null;
        $phone = $input['phone'] ?? null;
        $source = $input['source'] ?? 'General Click';
        $message = $input['message'] ?? 'Interacted via portal elements.';

        try {
            $db = \App\Services\Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO `leads` (name, email, phone, source, message) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $email, $phone, $source, $message]);

            $this->json(['success' => true, 'message' => 'Lead logged successfully.']);

        } catch (\Exception $e) {
            error_log("Database error in HomeController@logLead: " . $e->getMessage());
            $this->json(['success' => false, 'error' => 'Database persistence failure.'], 500);
        }
    }

    /**
     * GET /tools/vastu-score
     */
    public function vastuScoreTool(): void
    {
        $this->render('pages/vastu_score_tool', [
            'title' => 'Vastu Score Audit Tool - Narayani Portal',
            'meta_description' => 'Test your home spatial alignment coordinates using our complimentary audit checklist wizard.'
        ]);
    }

    /**
     * POST /api/vastu-score
     */
    public function calculateVastuScore(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }

        if (!\App\Helpers\RateLimiter::check('tool_submission', 10, 3600)) {
            $this->json(['error' => 'Too many submissions. Please wait an hour.'], 429);
            return;
        }
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $phone = $input['phone'] ?? '';

        $entrance = $input['entrance'] ?? '';
        $kitchen = $input['kitchen'] ?? '';
        $bedroom = $input['bedroom'] ?? '';
        $toilet = $input['toilet'] ?? '';
        $shape = $input['shape'] ?? '';

        if (empty($name) || (empty($email) && empty($phone))) {
            $this->json(['success' => false, 'error' => 'Gated coordinates (name and email or phone) are required.'], 400);
            return;
        }

        try {
            $db = \App\Services\Database::getConnection();

            $message = "Vastu audit answers: Entrance: {$entrance}, Kitchen: {$kitchen}, Bedroom: {$bedroom}, Toilet: {$toilet}, Shape: {$shape}";
            
            // Persist Lead
            $stmt = $db->prepare("
                INSERT INTO `leads` (name, email, phone, source, message) 
                VALUES (?, ?, ?, 'Vastu Score Tool', ?)
            ");
            $stmt->execute([$name, $email, $phone, $message]);

            // Calculate Vastu Score
            $result = \App\Helpers\VastuScorer::score([
                'entrance' => $entrance,
                'kitchen' => $kitchen,
                'bedroom' => $bedroom,
                'toilet' => $toilet,
                'shape' => $shape
            ]);

            $this->json([
                'success' => true,
                'score' => $result['score'],
                'advice' => $result['advice']
            ]);

        } catch (\Exception $e) {
            error_log("Error in HomeController@calculateVastuScore: " . $e->getMessage());
            $this->json(['success' => false, 'error' => 'A system error occurred. Please try again.'], 500);
        }
    }

    /**
     * GET /tools/sun-moon-sign
     */
    public function astroCalculatorTool(): void
    {
        $this->render('pages/astro_calculator_tool', [
            'title' => 'Cosmic Sign Finder - Narayani Portal',
            'meta_description' => 'Calculate your primary solar alignments coordinates and discover your astrological indicators.'
        ]);
    }

    /**
     * POST /api/sun-moon-sign
     */
    public function calculateAstroSign(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }

        if (!\App\Helpers\RateLimiter::check('tool_submission', 10, 3600)) {
            $this->json(['error' => 'Too many submissions. Please wait an hour.'], 429);
            return;
        }
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $phone = $input['phone'] ?? '';
        $birthDate = $input['birth_date'] ?? '';

        if (empty($name) || (empty($email) && empty($phone)) || empty($birthDate)) {
            $this->json(['success' => false, 'error' => 'All gated coordinates (name, email/phone, birth date) are required.'], 400);
            return;
        }

        try {
            $db = \App\Services\Database::getConnection();

            $message = "Birth date for astro sign calculation: {$birthDate}";
            
            // Persist Lead
            $stmt = $db->prepare("
                INSERT INTO `leads` (name, email, phone, source, message) 
                VALUES (?, ?, ?, 'Astro Sign Tool', ?)
            ");
            $stmt->execute([$name, $email, $phone, $message]);

            // Determine Sun Sign based on birth date (Gregorian)
            $time = strtotime($birthDate);
            $day = (int)date('d', $time);
            $month = (int)date('m', $time);

            $sign = "";
            $ruler = "";
            $element = "";
            $description = "";

            if (($month == 3 && $day >= 21) || ($month == 4 && $day <= 19)) {
                $sign = "Aries"; $ruler = "Mars"; $element = "Fire";
                $description = "Pioneering, energetic, courageous, and direct. You initiate actions with confidence.";
            } elseif (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) {
                $sign = "Taurus"; $ruler = "Venus"; $element = "Earth";
                $description = "Patient, reliable, warmhearted, and loving. You seek stability and comfort.";
            } elseif (($month == 5 && $day >= 21) || ($month == 6 && $day <= 20)) {
                $sign = "Gemini"; $ruler = "Mercury"; $element = "Air";
                $description = "Adaptable, versatile, communicative, and witty. You enjoy intellectual stimulation.";
            } elseif (($month == 6 && $day >= 21) || ($month == 7 && $day <= 22)) {
                $sign = "Cancer"; $ruler = "Moon"; $element = "Water";
                $description = "Emotional, loving, intuitive, and protective. You cherish close domestic bonds.";
            } elseif (($month == 7 && $day >= 23) || ($month == 8 && $day <= 22)) {
                $sign = "Leo"; $ruler = "Sun"; $element = "Fire";
                $description = "Generous, warmhearted, creative, and enthusiastic. You project a bright, active aura.";
            } elseif (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) {
                $sign = "Virgo"; $ruler = "Mercury"; $element = "Earth";
                $description = "Analytical, modest, helper-oriented, and practical. You strive for precision.";
            } elseif (($month == 9 && $day >= 23) || ($month == 10 && $day <= 22)) {
                $sign = "Libra"; $ruler = "Venus"; $element = "Air";
                $description = "Diplomatic, charming, balance-loving, and peaceable. You seek harmony in relations.";
            } elseif (($month == 10 && $day >= 23) || ($month == 11 && $day <= 21)) {
                $sign = "Scorpio"; $ruler = "Mars / Pluto"; $element = "Water";
                $description = "Determined, forceful, intuitive, and passionate. You possess deep energetic strength.";
            } elseif (($month == 11 && $day >= 22) || ($month == 12 && $day <= 21)) {
                $sign = "Sagittarius"; $ruler = "Jupiter"; $element = "Fire";
                $description = "Optimistic, freedom-loving, honest, and intellectual. You seek spatial and spiritual wisdom.";
            } elseif (($month == 12 && $day >= 22) || ($month == 1 && $day <= 19)) {
                $sign = "Capricorn"; $ruler = "Saturn"; $element = "Earth";
                $description = "Practical, prudent, ambitious, and disciplined. You build secure structural hierarchies.";
            } elseif (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) {
                $sign = "Aquarius"; $ruler = "Saturn / Uranus"; $element = "Air";
                $description = "Friendly, humanitarian, honest, and original. You value intellectual freedom.";
            } else {
                $sign = "Pisces"; $ruler = "Jupiter / Neptune"; $element = "Water";
                $description = "Imaginative, sensitive, compassionate, and intuitive. You resonate with cosmic cycles.";
            }

            $this->json([
                'success' => true,
                'sign' => $sign,
                'ruler' => $ruler,
                'element' => $element,
                'description' => $description
            ]);

        } catch (\Exception $e) {
            error_log("Error in HomeController@calculateAstroSign: " . $e->getMessage());
            $this->json(['success' => false, 'error' => 'A system error occurred. Please try again.'], 500);
        }
    }

    /**
     * GET /locale
     */
    public function setLocale(): void
    {
        $set = $_GET['set'] ?? 'en';
        if (in_array($set, ['en', 'hi'], true)) {
            \App\Helpers\Translator::setLocale($set);
        }
        
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: " . $referer);
        exit;
    }

    /**
     * GET /sitemap.xml
     */
    public function sitemap(): void
    {
        $urls = [
            'https://narayani.yuktaa.com/',
            'https://narayani.yuktaa.com/services',
            'https://narayani.yuktaa.com/about',
            'https://narayani.yuktaa.com/contact',
            'https://narayani.yuktaa.com/booking',
            'https://narayani.yuktaa.com/privacy-policy',
            'https://narayani.yuktaa.com/terms-conditions',
            'https://narayani.yuktaa.com/refund-policy',
        ];

        try {
            $db = \App\Services\Database::getConnection();

            // Fetch active services
            $stmt = $db->query("SELECT slug FROM `services` WHERE `is_active` = 1");
            $services = $stmt->fetchAll() ?: [];
            foreach ($services as $srv) {
                $urls[] = 'https://narayani.yuktaa.com/services/' . urlencode($srv['slug']);
            }

            // Fetch published blog posts if table exists
            $blogStmt = $db->query("SELECT slug FROM `blog_posts` WHERE `is_published` = 1");
            if ($blogStmt) {
                $blogs = $blogStmt->fetchAll() ?: [];
                foreach ($blogs as $post) {
                    $urls[] = 'https://narayani.yuktaa.com/blog/' . urlencode($post['slug']);
                }
            }
        } catch (\Exception $e) {
            error_log("Database error in HomeController@sitemap: " . $e->getMessage());
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($url) . "</loc>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>" . ($url === 'https://narayani.yuktaa.com/' ? '1.0' : '0.8') . "</priority>\n";
            echo "  </url>\n";
        }
        echo '</urlset>';
        exit;
    }
}

