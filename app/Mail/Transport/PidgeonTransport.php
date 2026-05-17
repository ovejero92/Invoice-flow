<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class PidgeonTransport extends AbstractTransport
{
    public function __construct(
        protected string $baseUrl,
        protected string $defaultFrom,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());
        $to = $email->getTo()[0] ?? null;

        if ($to === null) {
            return;
        }

        $from = $email->getFrom()[0] ?? null;
        $fromAddress = $from?->getAddress() ?? $this->defaultFrom;

        $html = $email->getHtmlBody() ?? $email->getTextBody() ?? '';

        $response = Http::timeout(15)
            ->acceptJson()
            ->post(rtrim($this->baseUrl, '/').'/send', [
                'to' => $to->getAddress(),
                'subject' => $email->getSubject() ?? '(sin asunto)',
                'html' => is_string($html) ? $html : (string) $html,
                'from' => $fromAddress,
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Pidgeon no pudo enviar el correo: '.$response->body());
        }
    }

    public function __toString(): string
    {
        return 'pidgeon';
    }
}
