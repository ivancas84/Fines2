<?php

declare(strict_types=1);

namespace ConstanciasApp\Services;

use ConstanciasApp\Core\Config;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

final class MailService
{
    public function __construct(private readonly Config $config)
    {
    }

    public function isConfigured(): bool
    {
        return $this->config->string('SMTP_HOST', '') !== ''
            && $this->config->string('SMTP_USER', '') !== ''
            && $this->config->string('SMTP_FROM_ADDRESS', '') !== '';
    }

    /**
     * @param list<string> $to
     * @param list<string> $bcc
     * @param list<array{path: string, name?: string}> $attachments
     */
    public function sendHtml(
        array $to,
        string $subject,
        string $htmlBody,
        array $bcc = [],
        array $attachments = [],
    ): void {
        if (!$this->isConfigured()) {
            throw new \RuntimeException('El envío de email no está configurado (SMTP_* en .env).');
        }

        $to = array_values(array_filter(array_map('trim', $to), static fn (string $v): bool => $v !== ''));
        if ($to === []) {
            throw new \InvalidArgumentException('No hay destinatarios de email.');
        }

        $maxAttempts = max(1, (int) $this->config->string('SMTP_MAX_ATTEMPTS', '3'));
        $attempt = 0;
        $lastError = '';

        while ($attempt < $maxAttempts) {
            $attempt++;
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = $this->config->string('SMTP_HOST');
                $mail->SMTPAuth = true;
                $mail->Username = $this->config->string('SMTP_USER');
                $mail->Password = $this->config->string('SMTP_PASSWORD');
                $secure = strtolower($this->config->string('SMTP_SECURE', 'tls'));
                $mail->SMTPSecure = $secure === 'ssl'
                    ? PHPMailer::ENCRYPTION_SMTPS
                    : PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = (int) $this->config->string('SMTP_PORT', '587');
                $mail->CharSet = 'UTF-8';

                $fromAddress = $this->config->string('SMTP_FROM_ADDRESS');
                $fromName = $this->config->string('SMTP_FROM_NAME', 'Constancias');
                $mail->setFrom($fromAddress, $fromName);

                foreach ($to as $address) {
                    $mail->addAddress($address);
                }
                foreach ($bcc as $address) {
                    $address = trim($address);
                    if ($address !== '') {
                        $mail->addBCC($address);
                    }
                }

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $htmlBody;
                $mail->AltBody = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody)));

                foreach ($attachments as $attachment) {
                    $path = (string) ($attachment['path'] ?? '');
                    if ($path === '' || !is_file($path)) {
                        continue;
                    }
                    $name = (string) ($attachment['name'] ?? basename($path));
                    $mail->addAttachment($path, $name);
                }

                $mail->send();

                return;
            } catch (MailException $exception) {
                $lastError = $exception->getMessage();
                if ($attempt < $maxAttempts) {
                    sleep(2);
                }
            }
        }

        throw new \RuntimeException(
            "No se pudo enviar el email después de {$maxAttempts} intentos: {$lastError}",
        );
    }
}
