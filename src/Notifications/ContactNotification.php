<?php

namespace App\Notifications;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactNotification
{

    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notify($contact, $productOwner) {

        $email = new Email();
        $email
            ->from($contact->getEmail())
            ->to($productOwner)
            ->text($contact->getMessage());

        $this->mailer->send($email);
    }

}