<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Helpers/Env.php';
\App\Helpers\Env::load(__DIR__ . '/../.env');

$_SERVER['REDIRECT_STATUS'] = 200;
if (function_exists('putenv')) {
    putenv("REDIRECT_STATUS=200");
}

// Begin session
if (session_status() === PHP_SESSION_NONE) {
    // Secure session cookies
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_secure', '1');   // Only over HTTPS
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.use_strict_mode', '1');
    session_start();
}

// Load translator helper globally
require_once __DIR__ . '/../src/Helpers/Translator.php';


// 1. PSR-4 Autoloading
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 2. Initialize router
use App\Helpers\Router;

$router = new Router();

// 3. Register route groups & paths
// Public routes
$router->get('/', 'HomeController@index');
$router->get('/services', 'HomeController@services');
$router->get('/services/{slug}', 'HomeController@serviceDetail');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');
$router->post('/contact', 'HomeController@submitContact');
$router->post('/api/log-lead', 'HomeController@logLead');
$router->get('/booking', 'HomeController@booking');
$router->get('/booking/receipt/{id}', 'BookingApiController@downloadReceipt');
$router->get('/privacy-policy', 'HomeController@privacyPolicy');
$router->get('/terms-conditions', 'HomeController@termsConditions');
$router->get('/refund-policy', 'HomeController@refundPolicy');
$router->get('/api/available-slots', 'BookingApiController@availableSlots');
$router->post('/api/book', 'BookingApiController@book');
$router->post('/api/verify-payment', 'BookingApiController@verifyPayment');
$router->get('/assets/vector', 'HomeController@vector');
$router->get('/generate-asset', 'HomeController@generateAsset');
$router->get('/design-system', 'HomeController@designSystem');
$router->get('/blog', 'BlogController@index');
$router->get('/blog/{slug}', 'BlogController@show');

$router->get('/locale', 'HomeController@setLocale');


$router->get('/tools/vastu-score', 'HomeController@vastuScoreTool');
$router->post('/api/vastu-score', 'HomeController@calculateVastuScore');
$router->get('/tools/sun-moon-sign', 'HomeController@astroCalculatorTool');
$router->post('/api/sun-moon-sign', 'HomeController@calculateAstroSign');

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

$router->get('/admin/login', 'AdminController@showLogin');
$router->post('/admin/login', 'AdminController@login');
$router->get('/admin/logout', 'AdminController@logout');

// Authenticated customer routes
$router->group('/dashboard', function (Router $r) {
    $r->get('/', 'CustomerController@index');
    $r->get('/bookings', 'CustomerController@bookings');
    $r->get('/profile', 'CustomerController@profile');
    $r->post('/profile', 'CustomerController@updateProfile');
    $r->post('/review', 'CustomerController@submitReview');
});

// Admin routes group
$router->group('/admin', function (Router $r) {
    $r->get('/', 'AdminController@dashboard');
    $r->get('/bookings', 'AdminController@bookings');
    $r->post('/bookings/upload-report', 'AdminController@uploadReport');
    $r->get('/bookings/export', 'AdminController@exportBookings');
    $r->get('/services', 'AdminController@services');
    $r->get('/services/create', 'AdminController@createService');
    $r->post('/services/create', 'AdminController@storeService');
    $r->get('/services/edit/{id}', 'AdminController@editService');
    $r->post('/services/edit/{id}', 'AdminController@updateService');
    $r->post('/services/delete/{id}', 'AdminController@deleteService');
    $r->get('/profile', 'AdminController@profile');
    $r->post('/profile', 'AdminController@updateProfile');
    $r->get('/marketing', 'AdminController@marketing');
    $r->post('/testimonials/approve/{id}', 'AdminController@approveTestimonial');
    $r->post('/testimonials/feature/{id}', 'AdminController@featureTestimonial');
    $r->post('/blog/create', 'AdminController@storeBlogPost');
    $r->post('/blog/edit/{id}', 'AdminController@updateBlogPost');
    $r->post('/leads/address/{id}', 'AdminController@addressLead');
});

// 4. Dispatch the request
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

$router->dispatch($requestMethod, $requestUri);
