<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    private $em;
    private $encoder;

    public function __construct(string $name = null, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        parent::__construct($name);

        $this->em = $em;
        $this->encoder = $encoder;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new Admin.')
            ->setHelp('This command allows you to create an administrator...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Admin Creator',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');

        $question = new Question('Please enter the username [email] : ');
        $username = $helper->ask($input, $output, $question);

        $question = new Question('Please enter the password : ');
        $question->setHidden(true);
        $password = $helper->ask($input, $output, $question);

        $user = (new User())
            ->setEmail($username)
            ->setFirstName('admin')
            ->setLastName("admin")
            ->setRoles(['ROLE_ADMIN']);
        $passwordEncoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($passwordEncoded);

        $this->em->persist($user);
        $this->em->flush();

        return Command::SUCCESS;
    }
}
