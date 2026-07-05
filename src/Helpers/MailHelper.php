<?php
declare(strict_types=1);

namespace App\Helpers;

class MailHelper
{
    /**
     * Send email with attachment and log it.
     */
    public static function sendReceiptEmail(string $toEmail, string $userName, string $pdfPath, float $amount): bool
    {
        $subject = "Your Narayani Alignment Receipt & Session Details";
        $from = "portal@narayani.yuktaa.com";

        // Read PDF content and base64 encode it
        $pdfContent = "";
        if (file_exists($pdfPath)) {
            $pdfContent = chunk_split(base64_encode(file_get_contents($pdfPath)));
        }
        $filename = basename($pdfPath);

        // Boundary 
        $semiRand = md5((string)time()); 
        $mimeBoundary = "==Multipart_Boundary_x{$semiRand}x"; 

        // Headers for attachment
        $headers = "From: {$from}\r\n" .
                   "MIME-Version: 1.0\r\n" .
                   "Content-Type: multipart/mixed;\r\n" .
                   " boundary=\"{$mimeBoundary}\"";

        // Multipart boundary 
        $message = "--{$mimeBoundary}\r\n" .
                   "Content-Type: text/html; charset=\"UTF-8\"\r\n" .
                   "Content-Transfer-Encoding: 7bit\r\n\r\n" .
                   "<html><body>" .
                   "<h2>Greetings {$userName},</h2>" .
                   "<p>Your session payment of <strong>INR " . number_format($amount, 2) . "</strong> has been confirmed successfully.</p>" .
                   "<p>Please find attached your official alignment receipt (PDF). You can access your session coordinates and consultation details directly on your seeker portal dashboard.</p>" .
                   "<br><p>In Harmony,<br>Narayani Portal Administration</p>" .
                   "</body></html>\r\n\r\n"; 

        // Attachment
        if (!empty($pdfContent)) {
            $message .= "--{$mimeBoundary}\r\n" .
                        "Content-Type: application/octet-stream;\r\n" .
                        " name=\"{$filename}\"\r\n" .
                        "Content-Description: {$filename}\r\n" .
                        "Content-Disposition: attachment;\r\n" .
                        " filename=\"{$filename}\"\r\n" .
                        "Content-Transfer-Encoding: base64\r\n\r\n" .
                        $pdfContent . "\r\n\r\n";
        }
        $message .= "--{$mimeBoundary}--";

        // Log the email for verification (even if mail() is not configured on local environment)
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }
        $logFile = $logDir . '/mail.log';
        $logEntry = "[" . date('Y-m-d H:i:s') . "] TO: {$toEmail} | SUBJECT: {$subject} | ATTACHMENT: {$filename}\n";
        error_log($logEntry, 3, $logFile);

        // Send mail using native PHP utility
        return @mail($toEmail, $subject, $message, $headers);
    }
}
