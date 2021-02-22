<?php
// src/Controller/MailerController.php
namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class MailerService
{

    private $mailerInterface;
    private $twig;

    public function __construct(MailerInterface $mailerInterface, Environment $twig)
    {
        $this->mailerInterface = $mailerInterface;
        $this->twig = $twig;
    }
    public function sendEmail(string $UserPassword, string $Useremail): void
    {
        $email = (new Email())
            ->from('preofesseur@moodle.fr')
            ->to($Useremail)
            ->subject('The credentials to login into our Moodle')
            ->text('Sending emails is fun again!')
            ->html("<p>Voici le password et l'email pour s'authentifier </p><br><br>
            <p>Email: $Useremail </p>
            <p>Password: $UserPassword </p>
            ");

        $this->mailerInterface->send($email);
    }
}
