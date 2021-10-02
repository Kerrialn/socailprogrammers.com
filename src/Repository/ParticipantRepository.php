<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Participant;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use function dump;

final class ParticipantRepository
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        $this->repository = $this->entityManager->getRepository(Participant::class);
    }

    public function persist(Participant $participant)
    {
        $this->entityManager->persist($participant);
    }

    public function flush()
    {
        $this->entityManager->flush();
    }

    public function remove(Participant $participant)
    {
        $this->entityManager->remove($participant);
    }

    public function save(Participant $participant)
    {
        $this->entityManager->persist($participant);
        $this->entityManager->flush();
    }

    public function isUserAttending(Event $event, UserInterface $user)
    {
        return $this->repository->findOneBy([
            'event' => $event,
            'user' => $user
        ]);
    }
}
