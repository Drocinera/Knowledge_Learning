<?php

namespace App\Command;

use App\Entity\Users;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userRepo = $this->em->getRepository(Users::class);
        $roleRepo = $this->em->getRepository(Role::class);

        // 🔐 Récupération des variables d’environnement
        $email = $_ENV['ADMIN_EMAIL'] ?? null;
        $password = $_ENV['ADMIN_PASSWORD'] ?? null;

        if (!$email || !$password) {
            $output->writeln('<error>ADMIN_EMAIL ou ADMIN_PASSWORD non définis.</error>');
            return Command::FAILURE;
        }

        // 🔍 Vérifie si l'admin existe déjà
        $existingUser = $userRepo->findOneBy(['email' => $email]);

        if ($existingUser) {
            $output->writeln('Admin already exists.');
            return Command::SUCCESS;
        }

        // 🔍 Vérifie si le rôle existe
        $role = $roleRepo->findOneBy(['name' => 'ROLE_ADMIN']);

        // 🔥 Création du rôle si absent
        if (!$role) {
            $output->writeln('ROLE_ADMIN not found, creating it...');

            $role = new Role();
            $role->setName('ROLE_ADMIN');
            $role->setDescription('Administrator');

            $this->em->persist($role);
            $this->em->flush();
        }

        // 👤 Création de l'utilisateur admin
        $user = new Users();
        $user->setEmail($email);
        $user->setRole($role);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('<info>Admin created successfully.</info>');

        return Command::SUCCESS;
    }
}