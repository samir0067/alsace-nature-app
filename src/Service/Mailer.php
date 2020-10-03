<?php


namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private $mailer;

    private $from;

    /**
     * Mailer constructor.
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer, $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function sendMail($subjects, $destinataire, $view, $variables)
    {
        if (!filter_var($destinataire, FILTER_VALIDATE_EMAIL)) {
            return'failed to log';
        }

        $email = (new TemplatedEmail())
            ->from($this->from)
            ->to($destinataire)
            ->subject($subjects)
            ->htmlTemplate('mail/'.$view.'.html.twig')
            ->context(['data' => $variables]);

        try {
            $this->mailer->send($email);
            return "success";
        } catch (TransportExceptionInterface $e) {
            return "failed with message :".$e->getMessage();
        }
    }
}
