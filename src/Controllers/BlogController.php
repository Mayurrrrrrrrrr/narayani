<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\Database;
use App\Helpers\HtmlHelper;
use Exception;

class BlogController extends BaseController
{
    /**
     * GET /blog
     */
    public function index(): void
    {
        $categories = [];
        $posts = [];
        $selectedCategorySlug = $_GET['category'] ?? '';
        
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 6;
        $offset = ($page - 1) * $limit;
        $totalCount = 0;

        try {
            $db = Database::getConnection();

            // Fetch categories
            $categories = $db->query("SELECT * FROM `service_categories`")->fetchAll() ?: [];

            // Base SQL count and fetch
            $countSql = "SELECT COUNT(*) as count FROM `blog_posts` b WHERE b.is_published = 1";
            $fetchSql = "
                SELECT b.*, c.name_en as category_name_en, c.name_hi as category_name_hi, c.slug as category_slug 
                FROM `blog_posts` b
                LEFT JOIN `service_categories` c ON b.category_id = c.id
                WHERE b.is_published = 1
            ";

            
            $params = [];
            if (!empty($selectedCategorySlug)) {
                $countSql .= " AND b.category_id = (SELECT id FROM `service_categories` WHERE slug = ? LIMIT 1)";
                $fetchSql .= " AND b.category_id = (SELECT id FROM `service_categories` WHERE slug = ? LIMIT 1)";
                $params[] = $selectedCategorySlug;
            }

            // Get total count
            $stmtCount = $db->prepare($countSql);
            $stmtCount->execute($params);
            $totalCount = (int)($stmtCount->fetch()['count'] ?? 0);

            // Fetch paginated posts
            $fetchSql .= " ORDER BY b.published_at DESC LIMIT ? OFFSET ?";
            $stmtFetch = $db->prepare($fetchSql);
            
            // PDO parameters for LIMIT and OFFSET must be integers if emulate prepares is off, or bound correctly.
            // Executing with an array casts values to strings, which can fail limit/offset in some drivers.
            // Let's bind parameters explicitly.
            if (!empty($selectedCategorySlug)) {
                $stmtFetch->bindValue(1, $selectedCategorySlug, \PDO::PARAM_STR);
                $stmtFetch->bindValue(2, $limit, \PDO::PARAM_INT);
                $stmtFetch->bindValue(3, $offset, \PDO::PARAM_INT);
            } else {
                $stmtFetch->bindValue(1, $limit, \PDO::PARAM_INT);
                $stmtFetch->bindValue(2, $offset, \PDO::PARAM_INT);
            }
            $stmtFetch->execute();
            $posts = $stmtFetch->fetchAll() ?: [];

        } catch (Exception $e) {
            error_log("BlogController@index error: " . $e->getMessage());
        }

        $totalPages = (int)ceil($totalCount / $limit) ?: 1;

        $this->render('pages/blog_list', [
            'title' => 'Cosmic Chronicles - Narayani Blog',
            'meta_description' => 'Unveil Vedic alignments, architectural geometries, and spiritual path details.',
            'categories' => $categories,
            'posts' => $posts,
            'selectedCategorySlug' => $selectedCategorySlug,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    /**
     * GET /blog/{slug}
     */
    public function show(string $slug): void
    {
        $post = null;
        $tocData = ['html' => '', 'toc' => []];
        $relatedServices = [];

        try {
            $db = Database::getConnection();

            // Fetch post with category info
            $stmt = $db->prepare("
                SELECT b.*, c.name_en as category_name_en, c.name_hi as category_name_hi, c.slug as category_slug, c.id as category_id
                FROM `blog_posts` b
                LEFT JOIN `service_categories` c ON b.category_id = c.id
                WHERE b.slug = ? AND b.is_published = 1
                LIMIT 1
            ");

            $stmt->execute([$slug]);
            $post = $stmt->fetch() ?: null;

            if ($post === null) {
                // Return styled 404 response
                http_response_code(404);
                $this->render('pages/404', [
                    'title' => 'Post Not Found - Narayani Portal',
                    'meta_description' => 'The requested blog post coordinates do not exist.'
                ]);
                return;
            }

            // Generate Table of Contents
            $postContent = db_trans($post, 'content');
            $tocData = HtmlHelper::generateTOC($postContent);


            // Fetch targeted service CTAs matching the article's category
            if (!empty($post['category_id'])) {
                $serviceStmt = $db->prepare("
                    SELECT * FROM `services` 
                    WHERE `category_id` = ? AND `is_active` = 1 
                    LIMIT 3
                ");
                $serviceStmt->execute([$post['category_id']]);
                $relatedServices = $serviceStmt->fetchAll() ?: [];
            }

        } catch (Exception $e) {
            error_log("BlogController@show error: " . $e->getMessage());
        }

        $this->render('pages/blog_detail', [
            'title' => db_trans($post, 'title') . ' - Narayani Blog',
            'meta_description' => htmlspecialchars(db_trans($post, 'excerpt')),
            'post' => $post,

            'contentHtml' => $tocData['html'],
            'toc' => $tocData['toc'],
            'relatedServices' => $relatedServices
        ]);
    }
}
