<?php
declare(strict_types=1);

namespace App\Controllers;

class CustomerController extends BaseController
{
    public function __construct()
    {
        // Session gatekeeping
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * GET /dashboard
     */
    public function index(): void
    {
        $userId = $_SESSION['user_id'];
        $bookings = [];

        try {
            $db = \App\Services\Database::getConnection();

            // Fetch upcoming active bookings (confirmed/pending)
            $stmt = $db->prepare("
                SELECT b.*, s.title, s.duration, s.price_inr 
                FROM `bookings` b
                JOIN `services` s ON b.service_id = s.id
                WHERE b.user_id = ? AND b.scheduled_at >= NOW() AND b.status != 'cancelled'
                ORDER BY b.scheduled_at ASC
            ");
            $stmt->execute([$userId]);
            $bookings = $stmt->fetchAll() ?: [];

        } catch (\Exception $e) {
            error_log("Database error in CustomerController@index: " . $e->getMessage());
        }

        $this->render('pages/dashboard', [
            'title' => 'Seeker Dashboard - Narayani Portal',
            'meta_description' => 'View your upcoming alignments and access digital coordinates.',
            'bookings' => $bookings,
            'userName' => $_SESSION['user_name']
        ]);
    }

    /**
     * GET /dashboard/bookings
     */
    public function bookings(): void
    {
        $userId = $_SESSION['user_id'];
        $bookings = [];

        try {
            $db = \App\Services\Database::getConnection();

            // Fetch all bookings history
            $stmt = $db->prepare("
                SELECT b.*, s.title, s.duration, s.price_inr 
                FROM `bookings` b
                JOIN `services` s ON b.service_id = s.id
                WHERE b.user_id = ?
                ORDER BY b.scheduled_at DESC
            ");
            $stmt->execute([$userId]);
            $bookings = $stmt->fetchAll() ?: [];

        } catch (\Exception $e) {
            error_log("Database error in CustomerController@bookings: " . $e->getMessage());
        }

        $this->render('pages/dashboard_bookings', [
            'title' => 'My Bookings - Narayani Portal',
            'meta_description' => 'View transaction history and download alignment reports.',
            'bookings' => $bookings
        ]);
    }

    /**
     * GET /dashboard/profile
     */
    public function profile(): void
    {
        $userId = $_SESSION['user_id'];
        $user = null;

        try {
            $db = \App\Services\Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = ? LIMIT 1");
            $stmt->execute([$userId]);
            $user = $stmt->fetch() ?: null;

        } catch (\Exception $e) {
            error_log("Database error in CustomerController@profile: " . $e->getMessage());
        }

        // Decode default birth details
        $birthDetails = [];
        if ($user && !empty($user['birth_details'])) {
            $birthDetails = json_decode($user['birth_details'], true) ?: [];
        }

        $this->render('pages/dashboard_profile', [
            'title' => 'Seeker Profile - Narayani Portal',
            'meta_description' => 'Update your credentials and default birth configurations.',
            'user' => $user,
            'birthDetails' => $birthDetails,
            'success' => $_SESSION['profile_success'] ?? null
        ]);
        unset($_SESSION['profile_success']);
    }

    /**
     * POST /dashboard/profile
     */
    public function updateProfile(): void
    {
        $userId = $_SESSION['user_id'];
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $city = $_POST['city'] ?? '';
        
        $birthDate = $_POST['birth_date'] ?? '';
        $birthTime = $_POST['birth_time'] ?? '';
        $birthCity = $_POST['birth_city'] ?? '';

        if (empty($name)) {
            header('Location: /dashboard/profile');
            exit;
        }

        try {
            $db = \App\Services\Database::getConnection();

            $birthDetails = json_encode([
                'birth_date' => $birthDate,
                'birth_time' => $birthTime,
                'birth_city' => $birthCity
            ], JSON_UNESCAPED_UNICODE);

            $stmt = $db->prepare("
                UPDATE `users` 
                SET `name` = ?, `phone` = ?, `city` = ?, `birth_details` = ? 
                WHERE `id` = ?
            ");
            $stmt->execute([$name, $phone, $city, $birthDetails, $userId]);

            // Update session values
            $_SESSION['user_name'] = $name;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_city'] = $city;

            $_SESSION['profile_success'] = 'Profile coordinates successfully updated.';
            header('Location: /dashboard/profile');
            exit;

        } catch (\Exception $e) {
            error_log("Database error in CustomerController@updateProfile: " . $e->getMessage());
            header('Location: /dashboard/profile');
            exit;
        }
    }

    /**
     * POST /dashboard/review
     */
    public function submitReview(): void
    {
        $userId = $_SESSION['user_id'];
        $rating = (float)($_POST['rating'] ?? 5.0);
        $content = $_POST['content'] ?? '';

        if (empty($content)) {
            $_SESSION['review_error'] = 'Review content cannot be empty.';
            header('Location: /dashboard');
            exit;
        }

        try {
            $db = \App\Services\Database::getConnection();

            // Fetch user name and city
            $userStmt = $db->prepare("SELECT `name`, `city` FROM `users` WHERE `id` = ? LIMIT 1");
            $userStmt->execute([$userId]);
            $user = $userStmt->fetch();

            $clientName = $user['name'] ?? 'Anonymous Seeker';
            $clientCity = $user['city'] ?? 'India';

            $stmt = $db->prepare("
                INSERT INTO `testimonials` (client_name, client_city, rating, content_en, content_hi, is_featured, is_approved)
                VALUES (?, ?, ?, ?, ?, 0, 0)
            ");
            $stmt->execute([$clientName, $clientCity, $rating, $content, $content]);

            $_SESSION['review_success'] = 'Thank you! Your review has been submitted and is awaiting administrative verification.';

        } catch (\Exception $e) {
            error_log("Database error in CustomerController@submitReview: " . $e->getMessage());
            $_SESSION['review_error'] = 'Could not submit your review. Please try again.';
        }

        header('Location: /dashboard');
        exit;
    }
}
