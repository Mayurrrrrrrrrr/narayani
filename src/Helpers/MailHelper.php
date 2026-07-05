<?php
declare(strict_types=1);

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class MailHelper
{
    /**
     * Send email with attachment and log it.
     */
    public static function sendReceiptEmail(string $toEmail, string $userName, string $pdfPath, float $amount): bool
    {
        $subject = "Your Narayani Alignment Receipt & Session Details";
        $filename = basename($pdfPath);

        // Log the email for verification
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }
        $logFile = $logDir . '/mail.log';
        $logEntry = "[" . date('Y-m-d H:i:s') . "] (PHPMailer) TO: {$toEmail} | SUBJECT: {$subject} | ATTACHMENT: {$filename}\n";
        error_log($logEntry, 3, $logFile);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = Env::get('MAIL_HOST', 'smtp.gmail.com');
            $mail->SMTPAuth   = true;
            $mail->Username   = Env::get('MAIL_USER');
            $mail->Password   = Env::get('MAIL_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom(Env::get('MAIL_FROM', 'portal@narayani.yuktaa.com'), 'Narayani Portal');
            $mail->addAddress($toEmail, $userName);
            $mail->Subject = $subject;
            $mail->isHTML(true);
            $mail->Body = "<html><body>" .
                          "<h2>Greetings {$userName},</h2>" .
                          "<p>Your session payment of <strong>INR " . number_format($amount, 2) . "</strong> has been confirmed successfully.</p>" .
                          "<p>Please find attached your official alignment receipt (PDF). You can access your session coordinates and consultation details directly on your seeker portal dashboard.</p>" .
                          "<br><p>In Harmony,<br>Narayani Portal Administration</p>" .
                          "</body></html>";

            if (file_exists($pdfPath)) {
                $mail->addAttachment($pdfPath);
            }

            $mail->send();
            return true;
        } catch (\Exception $e) {
            error_log('PHPMailer failed: ' . $e->getMessage());
            return false;
        }
    }
}
