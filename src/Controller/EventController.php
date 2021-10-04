<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participant;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Repository\ParticipantRepository;
use App\Service\StaticGoogleMapService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class EventController extends AbstractController
{

    public function __construct(
        private EventRepository $eventRepository,
        private ParticipantRepository $participantRepository,
        private StaticGoogleMapService $staticGoogleMapService,
    ) {
    }

    #[Route('/events/create', name: 'event.create')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $event = new Event();
        $form = $this->createForm(EventFormType::class, $event);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           return $this->handleCreateForm($form, $event);
        }

        return $this->render('event/create.html.twig', [
            'eventForm' => $form->createView()
        ]);
    }


    #[Route('/events', name: 'events')]
    #[IsGranted('ROLE_ADMIN')]
    public function events(): Response
    {
        $events = $this->eventRepository->findAllIncomplete();
        return $this->render('app/events.html.twig', [
            'events' => $events
        ]);
    }

    #[Route('/events/{id}', name: 'event')]
    #[IsGranted('ROLE_ADMIN')]
    public function event(Event $event): Response
    {
        return $this->render('app/event.html.twig', [
            'event' => $event
        ]);
    }

    #[Route('/events/{id}/join', name: 'join.event')]
    #[IsGranted('ROLE_ADMIN')]
    public function join(Event $event): RedirectResponse
    {
        $participant = $this->participantRepository->isUserAttending($event, $this->getUser());

        if (!$participant) {
            $participant = new Participant();
            $participant->setUser($this->getUser());
            $participant->setEvent($event);
            $this->participantRepository->save($participant);

            $this->addFlash('', 'you are already attending this event');
        } else {
            $this->addFlash('error', sprintf('You are already attending %s', $event->getTitle()));
        }

        return $this->redirectToRoute('event', ['id' => $event->getId()]);
    }

    #[Route('/events/{id}/leave', name: 'leave.event')]
    #[IsGranted('ROLE_ADMIN')]
    public function leave(Event $event): RedirectResponse
    {
        /**
         * @var Participant $participant
         */
        $participant = $this->participantRepository->isUserAttending($event, $this->getUser());

        if($participant->getUser() === $event->getHost()){
            return $this->redirectToRoute('event', ['id' => $event->getId()]);
        }

        if (!$event->getIsAttending($participant->getUser())) {
            $this->addFlash('error', sprintf('You already aren\'t attending %s', $event->getTitle()));
        } else {
            $this->addFlash('success', sprintf('you have left %s', $event->getTitle()));

            $this->participantRepository->remove($participant);
            $this->participantRepository->flush();
        }

        return $this->redirectToRoute('event', ['id' => $event->getId()]);

    }

    private function handleCreateForm(FormInterface $form, Event $event) : RedirectResponse
    {
        $user = $this->getUser();
        $event->setHost($user);

        $isOnline = $form->get('isOnline')->getData();
        if($isOnline){
            $event->setIsOnline(true);
        }else{
            $event->setIsOnline(false);
        }

       $latitude = $form->get('latitude')->getData();
       $longitude = $form->get('longitude')->getData();

       $image = $this->staticGoogleMapService->getStaticImage($event, $this->getParameter('GOOGLE_MAPS_PUBLIC_KEY'));

       $event->setMap($image);

        $this->eventRepository->save($event);
        $this->getParticipant($user, $event);

        return $this->redirectToRoute('events');

    }

    private function getParticipant(UserInterface $user, Event $event) : Participant
    {
        $participant = new Participant();
        $participant->setUser($user);
        $participant->setEvent($event);
        $this->participantRepository->save($participant);

        return $participant;
    }
}
