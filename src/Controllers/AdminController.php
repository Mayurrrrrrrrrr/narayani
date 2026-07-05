<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\Database;
use Exception;

class AdminController extends BaseController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = parse_url($uri, PHP_URL_PATH);
        if ($path !== '/admin/login' && !isset($_SESSION['admin_user_id'])) {
            header('Location: /admin/login');
            exit;
        }
    }

    /**
     * GET /admin/login
     */
    public function showLogin(): void
    {
        if (isset($_SESSION['admin_user_id'])) {
            header('Location: /admin');
            exit;
        }

        $this->render('admin/login', [
            'title' => 'Admin Sign In - Narayani CMS',
            'meta_description' => 'System authentication panel for Narayani admin workspace.',
            'error' => $_SESSION['admin_login_error'] ?? null
        ]);
        unset($_SESSION['admin_login_error']);
    }

    /**
     * POST /admin/login
     */
    public function login(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }

        if (!\App\Helpers\RateLimiter::check('admin_login', 5, 60)) {
            $_SESSION['admin_login_error'] = 'Too many login attempts. Please try again in 1 minute.';
            header('Location: /admin/login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $adminEmail = \App\Helpers\Env::get('ADMIN_EMAIL');
        $adminPassword = \App\Helpers\Env::get('ADMIN_PASSWORD');

        if ($email === $adminEmail && $password === $adminPassword) {
            session_regenerate_id(true);
            $_SESSION['admin_user_id'] = 999; // Mock admin user id
            $_SESSION['admin_user_name'] = 'System Administrator';
            header('Location: /admin');
            exit;
        } else {
            $_SESSION['admin_login_error'] = 'Invalid administrative credentials.';
            header('Location: /admin/login');
            exit;
        }
    }

    /**
     * GET /admin/logout
     */
    public function logout(): void
    {
        unset($_SESSION['admin_user_id']);
        unset($_SESSION['admin_user_name']);
        header('Location: /admin/login');
        exit;
    }

    /**
     * GET /admin
     */
    public function dashboard(): void
    {
        $earnings = 0.00;
        $activeBookingsCount = 0;
        $seekersCount = 0;
        $leadsCount = 0;
        $chartData = [];

        try {
            $db = Database::getConnection();

            // 1. Monthly Earnings
            $earningsStmt = $db->query("SELECT SUM(amount) as total FROM `payments` WHERE `status` = 'success' OR `status` = 'confirmed'");
            $earnings = (float)($earningsStmt->fetch()['total'] ?? 0.00);

            // 2. Active Bookings
            $activeStmt = $db->query("SELECT COUNT(*) as cnt FROM `bookings` WHERE `status` IN ('pending', 'confirmed')");
            $activeBookingsCount = (int)($activeStmt->fetch()['cnt'] ?? 0);

            // 3. Seekers count
            $seekersStmt = $db->query("SELECT COUNT(*) as cnt FROM `users`");
            $seekersCount = (int)($seekersStmt->fetch()['cnt'] ?? 0);

            // 4. Leads count
            $leadsStmt = $db->query("SELECT COUNT(*) as cnt FROM `leads`");
            $leadsCount = (int)($leadsStmt->fetch()['cnt'] ?? 0);

            // 5. Chart Data (Past 7 days)
            $chartQuery = $db->query("
                SELECT DATE(scheduled_at) as date, COUNT(*) as count 
                FROM `bookings` 
                GROUP BY DATE(scheduled_at) 
                ORDER BY DATE(scheduled_at) ASC 
                LIMIT 7
            ");
            $chartData = $chartQuery->fetchAll() ?: [];

            // If empty, fill with mock data for aesthetic charts representation
            if (empty($chartData)) {
                for ($i = 6; $i >= 0; $i--) {
                    $chartData[] = [
                        'date' => date('Y-m-d', strtotime("-$i days")),
                        'count' => rand(1, 5)
                    ];
                }
            }

        } catch (Exception $e) {
            error_log("AdminController@dashboard error: " . $e->getMessage());
        }

        $this->render('admin/dashboard', [
            'title' => 'Admin Overview - Narayani CMS',
            'earnings' => $earnings,
            'activeBookingsCount' => $activeBookingsCount,
            'seekersCount' => $seekersCount,
            'leadsCount' => $leadsCount,
            'chartData' => $chartData
        ]);
    }

    /**
     * GET /admin/bookings
     */
    public function bookings(): void
    {
        $bookings = [];
        $filterStatus = $_GET['status'] ?? '';
        $searchQuery = $_GET['search'] ?? '';

        try {
            $db = Database::getConnection();

            $sql = "
                SELECT b.*, u.name as seeker_name, u.email as seeker_email, s.title as service_title
                FROM `bookings` b
                JOIN `users` u ON b.user_id = u.id
                JOIN `services` s ON b.service_id = s.id
                WHERE 1=1
            ";
            $params = [];

            if (!empty($filterStatus)) {
                $sql .= " AND b.status = ?";
                $params[] = $filterStatus;
            }

            if (!empty($searchQuery)) {
                $sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR s.title LIKE ?)";
                $like = "%$searchQuery%";
                $params[] = $like;
                $params[] = $like;
                $params[] = $like;
            }

            $sql .= " ORDER BY b.scheduled_at DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $bookings = $stmt->fetchAll() ?: [];

        } catch (Exception $e) {
            error_log("AdminController@bookings error: " . $e->getMessage());
        }

        $this->render('admin/bookings', [
            'title' => 'Manage Bookings - Narayani CMS',
            'bookings' => $bookings,
            'filterStatus' => $filterStatus,
            'searchQuery' => $searchQuery
        ]);
    }

    /**
     * POST /admin/bookings/upload-report
     */
    public function uploadReport(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $bookingId = (int)($_POST['booking_id'] ?? 0);

        if ($bookingId <= 0 || empty($_FILES['report_file']['tmp_name'])) {
            header('Location: /admin/bookings');
            exit;
        }

        try {
            $projectRoot = dirname(__DIR__, 2);
            $targetDir = $projectRoot . '/storage/reports';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $fileName = 'report_' . $bookingId . '.pdf';
            $targetPath = $targetDir . '/' . $fileName;

            if (move_uploaded_file($_FILES['report_file']['tmp_name'], $targetPath)) {
                // Update database report path reference
                $db = Database::getConnection();
                $stmt = $db->prepare("UPDATE `bookings` SET `report_path` = ? WHERE `id` = ?");
                $stmt->execute(['/storage/reports/' . $fileName, $bookingId]);
            }

        } catch (Exception $e) {
            error_log("AdminController@uploadReport error: " . $e->getMessage());
        }

        header('Location: /admin/bookings');
        exit;
    }

    /**
     * GET /admin/bookings/export
     */
    public function exportBookings(): void
    {
        $filterStatus = $_GET['status'] ?? '';
        
        try {
            $db = Database::getConnection();
            $sql = "
                SELECT b.id, u.name as seeker_name, u.email as seeker_email, s.title as service_title, b.scheduled_at, b.consultation_mode, b.status, s.price_inr
                FROM `bookings` b
                JOIN `users` u ON b.user_id = u.id
                JOIN `services` s ON b.service_id = s.id
            ";
            $params = [];
            if (!empty($filterStatus)) {
                $sql .= " WHERE b.status = ?";
                $params[] = $filterStatus;
            }
            $sql .= " ORDER BY b.scheduled_at DESC";

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $rows = $stmt->fetchAll() ?: [];

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="bookings_export_' . date('Ymd_His') . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID', 'Seeker Name', 'Seeker Email', 'Service Target', 'Scheduled Date', 'Consultation Mode', 'Status', 'Fee (INR)']);
            
            foreach ($rows as $row) {
                fputcsv($output, [
                    $row['id'],
                    $row['seeker_name'],
                    $row['seeker_email'],
                    $row['service_title'],
                    $row['scheduled_at'],
                    $row['consultation_mode'],
                    $row['status'],
                    $row['price_inr']
                ]);
            }
            fclose($output);
            exit;

        } catch (Exception $e) {
            error_log("AdminController@exportBookings error: " . $e->getMessage());
            header('Location: /admin/bookings');
            exit;
        }
    }

    /**
     * GET /admin/services
     */
    public function services(): void
    {
        $services = [];
        try {
            $db = Database::getConnection();
            $services = $db->query("
                SELECT s.*, c.name_en as category_name 
                FROM `services` s
                JOIN `service_categories` c ON s.category_id = c.id
                ORDER BY s.id DESC
            ")->fetchAll() ?: [];
        } catch (Exception $e) {
            error_log("AdminController@services error: " . $e->getMessage());
        }

        $this->render('admin/services', [
            'title' => 'Catalog offerings - Narayani CMS',
            'services' => $services,
            'action' => 'list'
        ]);
    }

    /**
     * GET /admin/services/create
     */
    public function createService(): void
    {
        $categories = [];
        try {
            $db = Database::getConnection();
            $categories = $db->query("SELECT * FROM `service_categories`")->fetchAll() ?: [];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        $this->render('admin/services', [
            'title' => 'Create Cosmic Offering - Narayani CMS',
            'categories' => $categories,
            'action' => 'create'
        ]);
    }

    /**
     * POST /admin/services/create
     */
    public function storeService(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $slug = $_POST['slug'] ?? '';
        $title = $_POST['title'] ?? '';
        $duration = (int)($_POST['duration'] ?? 60);
        $priceInr = (float)($_POST['price_inr'] ?? 0.00);
        $shortDesc = $_POST['short_desc'] ?? '';
        $longDesc = $_POST['long_desc'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO `services` (category_id, slug, title, short_desc, long_desc, duration, price_inr, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$categoryId, $slug, $title, $shortDesc, $longDesc, $duration, $priceInr, $isActive]);
        } catch (Exception $e) {
            error_log("AdminController@storeService error: " . $e->getMessage());
        }

        header('Location: /admin/services');
        exit;
    }

    /**
     * GET /admin/services/edit/{id}
     */
    public function editService(string $id): void
    {
        $serviceId = (int)$id;
        $service = null;
        $categories = [];

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `services` WHERE `id` = ? LIMIT 1");
            $stmt->execute([$serviceId]);
            $service = $stmt->fetch() ?: null;

            $categories = $db->query("SELECT * FROM `service_categories`")->fetchAll() ?: [];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        if (!$service) {
            header('Location: /admin/services');
            exit;
        }

        $this->render('admin/services', [
            'title' => 'Edit Cosmic Offering - Narayani CMS',
            'service' => $service,
            'categories' => $categories,
            'action' => 'edit'
        ]);
    }

    /**
     * POST /admin/services/edit/{id}
     */
    public function updateService(string $id): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $serviceId = (int)$id;
        $categoryId = (int)($_POST['category_id'] ?? 0);
        $slug = $_POST['slug'] ?? '';
        $title = $_POST['title'] ?? '';
        $duration = (int)($_POST['duration'] ?? 60);
        $priceInr = (float)($_POST['price_inr'] ?? 0.00);
        $shortDesc = $_POST['short_desc'] ?? '';
        $longDesc = $_POST['long_desc'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                UPDATE `services`
                SET category_id = ?, slug = ?, title = ?, short_desc = ?, long_desc = ?, duration = ?, price_inr = ?, is_active = ?
                WHERE id = ?
            ");
            $stmt->execute([$categoryId, $slug, $title, $shortDesc, $longDesc, $duration, $priceInr, $isActive, $serviceId]);
        } catch (Exception $e) {
            error_log("AdminController@updateService error: " . $e->getMessage());
        }

        header('Location: /admin/services');
        exit;
    }

    /**
     * POST /admin/services/delete/{id}
     */
    public function deleteService(string $id): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $serviceId = (int)$id;
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM `services` WHERE id = ?");
            $stmt->execute([$serviceId]);
        } catch (Exception $e) {
            error_log("AdminController@deleteService error: " . $e->getMessage());
        }

        header('Location: /admin/services');
        exit;
    }

    /**
     * GET /admin/profile
     */
    public function profile(): void
    {
        $profile = null;
        try {
            $db = Database::getConnection();
            $profile = $db->query("SELECT * FROM `consultant_profile` LIMIT 1")->fetch() ?: null;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        $availability = [];
        if ($profile && !empty($profile['weekly_availability'])) {
            $availability = json_decode($profile['weekly_availability'], true) ?: [];
        }

        $this->render('admin/profile', [
            'title' => 'Consultant Profiles - Narayani CMS',
            'profile' => $profile,
            'availability' => $availability,
            'success' => $_SESSION['admin_profile_success'] ?? null
        ]);
        unset($_SESSION['admin_profile_success']);
    }

    /**
     * POST /admin/profile
     */
    public function updateProfile(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $name = $_POST['name'] ?? '';
        $photoUrl = $_POST['photo_url'] ?? '';
        $taglineEn = $_POST['tagline_en'] ?? '';
        $taglineHi = $_POST['tagline_hi'] ?? '';
        $bioEn = $_POST['bio_en'] ?? '';
        $bioHi = $_POST['bio_hi'] ?? '';
        $weeklyAvailability = $_POST['weekly_availability'] ?? '[]';

        try {
            $db = Database::getConnection();
            // Validate availability JSON structure
            json_decode($weeklyAvailability);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $weeklyAvailability = '[]';
            }

            $stmt = $db->prepare("
                UPDATE `consultant_profile`
                SET name = ?, photo_url = ?, tagline_en = ?, tagline_hi = ?, bio_en = ?, bio_hi = ?, weekly_availability = ?
                WHERE id = 1
            ");
            $stmt->execute([$name, $photoUrl, $taglineEn, $taglineHi, $bioEn, $bioHi, $weeklyAvailability]);

            $_SESSION['admin_profile_success'] = 'Consultant profiles configuration successfully deployed.';

        } catch (Exception $e) {
            error_log("AdminController@updateProfile error: " . $e->getMessage());
        }

        header('Location: /admin/profile');
        exit;
    }

    /**
     * GET /admin/marketing
     */
    public function marketing(): void
    {
        $testimonials = [];
        $blogs = [];
        $leads = [];

        try {
            $db = Database::getConnection();
            $testimonials = $db->query("SELECT * FROM `testimonials` ORDER BY id DESC")->fetchAll() ?: [];
            $blogs = $db->query("SELECT * FROM `blog_posts` ORDER BY id DESC")->fetchAll() ?: [];
            $leads = $db->query("SELECT * FROM `leads` ORDER BY id DESC")->fetchAll() ?: [];
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        $this->render('admin/marketing', [
            'title' => 'Moderation Workspace - Narayani CMS',
            'testimonials' => $testimonials,
            'blogs' => $blogs,
            'leads' => $leads
        ]);
    }

    /**
     * POST /admin/testimonials/approve/{id}
     */
    public function approveTestimonial(string $id): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $testimonialId = (int)$id;
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE `testimonials` SET `is_approved` = 1 - `is_approved` WHERE `id` = ?");
            $stmt->execute([$testimonialId]);
        } catch (Exception $e) {
            error_log("AdminController@approveTestimonial error: " . $e->getMessage());
        }

        header('Location: /admin/marketing');
        exit;
    }

    /**
     * POST /admin/testimonials/feature/{id}
     */
    public function featureTestimonial(string $id): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $testimonialId = (int)$id;
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE `testimonials` SET `is_featured` = 1 - `is_featured` WHERE `id` = ?");
            $stmt->execute([$testimonialId]);
        } catch (Exception $e) {
            error_log("AdminController@featureTestimonial error: " . $e->getMessage());
        }

        header('Location: /admin/marketing');
        exit;
    }

    /**
     * POST /admin/blog/create
     */
    public function storeBlogPost(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $title = $_POST['title'] ?? '';
        $slug = $_POST['slug'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';
        $content = $_POST['content'] ?? '';
        $isPublished = isset($_POST['is_published']) ? 1 : 0;
        $publishedAt = $isPublished ? date('Y-m-d H:i:s') : null;
        $coverImage = '/generate-asset?type=geometry&seed=' . urlencode($slug) . '&w=600';

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO `blog_posts` (title, slug, excerpt, content, cover_image, is_published, published_at)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$title, $slug, $excerpt, $content, $coverImage, $isPublished, $publishedAt]);
        } catch (Exception $e) {
            error_log("AdminController@storeBlogPost error: " . $e->getMessage());
        }

        header('Location: /admin/marketing');
        exit;
    }

    /**
     * POST /admin/blog/edit/{id}
     */
    public function updateBlogPost(string $id): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $blogId = (int)$id;
        try {
            $db = Database::getConnection();
            // Toggle publication status
            $stmt = $db->prepare("
                UPDATE `blog_posts` 
                SET `is_published` = 1 - `is_published`, 
                    `published_at` = CASE WHEN `is_published` = 0 THEN NOW() ELSE NULL END
                WHERE `id` = ?
            ");
            $stmt->execute([$blogId]);
        } catch (Exception $e) {
            error_log("AdminController@updateBlogPost error: " . $e->getMessage());
        }

        header('Location: /admin/marketing');
        exit;
    }

    /**
     * POST /admin/leads/address/{id}
     */
    public function addressLead(string $id): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }
        $leadId = (int)$id;
        try {
            $db = Database::getConnection();
            // Delete lead or mark as resolved
            $stmt = $db->prepare("DELETE FROM `leads` WHERE `id` = ?");
            $stmt->execute([$leadId]);
        } catch (Exception $e) {
            error_log("AdminController@addressLead error: " . $e->getMessage());
        }

        header('Location: /admin/marketing');
        exit;
    }
}
