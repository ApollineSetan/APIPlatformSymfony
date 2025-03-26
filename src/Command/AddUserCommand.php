<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'add-user',
    description: 'Commande pour ajouter un utilisateur en BDD',
)]
class AddUserCommand extends Command
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstname', InputArgument::REQUIRED, 'Prénom utilisateur')
            ->addArgument('lastname', InputArgument::REQUIRED, 'Nom utilisateur')
            ->addArgument('email', InputArgument::REQUIRED, 'Email utilisateur')
            ->addArgument('password', InputArgument::REQUIRED, 'Mot de passe utilisateur')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupérer la valeur des arguments
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        // Récupérer l'utilisateur dans une variable
        $alreadyExistingUser = $this->userRepository->findOneBy(['email' => $email]);

        // Vérifier si le compte existe déjà
        if($alreadyExistingUser) {
            $io->error("This user already exists.");
            return Command::FAILURE;
        }

        // Créer un objet User
        $user = new User;
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setPassword($password);

        // Hasher le mot de passe
        $hashedPassword = $this->hasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        // Ajouter le compte en BDD
        $this->em->persist($user);
        $this->em->flush();

        $io->success('You successfully added a new User.');
        return Command::SUCCESS;
    }
}
