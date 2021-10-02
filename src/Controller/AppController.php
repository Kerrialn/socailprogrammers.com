<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\EventRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function dump;

class AppController extends AbstractController
{

    public function __construct(
        private EventRepository $eventRepository
    )
    {
    }

    #[Route('/', name: 'app.landing.page')]
    public function index(): Response
    {
        $upcomingEvents = $this->eventRepository->findAllUpcoming();

        return $this->render('app/index.html.twig', [
            'upcomingEvents' => $upcomingEvents
        ]);
    }


}
