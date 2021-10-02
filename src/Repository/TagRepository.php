<?php

namespace App\Repository;

use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TagRepository
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Tag::class);
    }

    public function persist(Tag $tag)
    {
        $this->entityManager->persist($tag);
    }

    public function flush(){
        $this->entityManager->flush();
    }

    public function find(int $id): ?Tag
    {
        return $this->repository->find($id);
    }

    public function findByTitleAndLevel(string $title, string $level) : ?Tag
    {
        return $this->repository->createQueryBuilder('t')
            ->andWhere('t.title = :title')
            ->setParameter('title', $title)
            ->andWhere('t.level = :level')
            ->setParameter('level', $level)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findTagByTitleAndUser(string $title, User $user) : ?Tag
    {
        return $this->repository->createQueryBuilder('t')
            ->andWhere('t.title = :title')
            ->setParameter('title', $title)
            ->leftJoin('t.users', 'u')
            ->andWhere('u.id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
