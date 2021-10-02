<?php

namespace App\Repository;

use App\Entity\Event;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class EventRepository
{

    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Event::class);
    }

    public function save(Event $event)
    {
        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

    public function findAllUpcoming()
    {

        $qb = $this->repository->createQueryBuilder('e');

        $qb->where($qb->expr()->gt('e.startsAt', ':now'));
        $qb->setParameter('now', (new DateTime())->getTimestamp());
        $qb->orderBy('e.createdAt');

        return $qb->getQuery()->getResult();
    }

    public function findAllIncomplete()
    {
        return $this->repository->createQueryBuilder('e')
            ->where('e.isComplete = false')
            ->orderBy('e.createdAt')
            ->getQuery()
            ->getResult();
    }

    public function find(Event $event)
    {
        return $this->repository->find($event);
    }
}
