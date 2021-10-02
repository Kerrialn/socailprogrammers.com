<?php

namespace App\Twig;

use App\Repository\ParticipantRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AttendingEvent extends AbstractExtension
{
    public function __construct(
        private ParticipantRepository $participantRepository
    )
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('isAttending', [$this, 'isAttending']),
        ];
    }

    public function isAttending($event, $user)
    {
        return !($this->participantRepository->isUserAttending($event, $user) === null);
    }
}
