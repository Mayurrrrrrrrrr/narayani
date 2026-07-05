<?php
declare(strict_types=1);

namespace App\Controllers;

class AuthController extends BaseController
{
    /**
     * GET /login
     */
    public function showLogin(): void
    {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }

        $this->render('pages/login', [
            'title' => 'Portal Sign In - Narayani Portal',
            'meta_description' => 'Log in to your seeker dashboard to view coordinates and receipts.',
            'error' => $_SESSION['login_error'] ?? null
        ]);
        unset($_SESSION['login_error']);
    }

    /**
     * POST /login
     */
    public function login(): void
    {
        if (!\App\Helpers\Csrf::validate()) {
            http_response_code(403);
            die('Invalid request.');
        }

        if (!\App\Helpers\RateLimiter::check('user_login', 5, 60)) {
            $_SESSION['login_error'] = 'Too many login attempts. Please try again in 1 minute.';
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Please enter your email and password.';
            header('Location: /login');
            exit;
        }

        try {
            $db = \App\Services\Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['user_city'] = $user['city'];

                header('Location: /dashboard');
                exit;
            } else {
                $_SESSION['login_error'] = 'Invalid email or password coordinates.';
                header('Location: /login');
                exit;
            }

        } catch (\Exception $e) {
            error_log("Database error in AuthController@login: " . $e->getMessage());
            $_SESSION['login_error'] = 'Database error. Please try again.';
            header('Location: /login');
            exit;
        }
    }

    /**
     * GET /logout
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        header('Location: /');
        exit;
    }
}
