<?php
declare(strict_types=1);

namespace App\Controllers;

use RuntimeException;

abstract class BaseController
{
    /**
     * Render a view template within a layout wrapper.
     */
    protected function render(string $viewPath, array $data = [], string $layout = 'app'): void
    {
        // Extract variables to local scope for the template
        extract($data);

        // Find the view file
        $baseTemplatesDir = dirname(__DIR__, 2) . '/templates';
        $viewFile = $baseTemplatesDir . '/' . ltrim($viewPath, '/') . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("View file template not found: {$viewFile}");
        }

        // Render view content to buffer
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Render layout
        $layoutFile = $baseTemplatesDir . "/layouts/{$layout}.php";
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            // If no layout layout, just output the template content
            echo $content;
        }
    }

    /**
     * Return JSON response
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }
}
