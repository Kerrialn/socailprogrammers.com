<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;


final class UserRepository
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager)
    {
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function findOneByEmail(string $email) : ?User
    {
       return $this->repository->findOneBy([
            'email' => $email
        ]);
    }

    public function save(UserInterface $user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
