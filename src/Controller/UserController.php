<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\AccountFormType;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function __construct(
        private TagRepository $tagRepository,
        private UserRepository $userRepository,
    )
    {
    }

    #[Route('/tag/{id}/remove', name: 'user.remove.tag')]
    public function removeTag($id): RedirectResponse
    {
        $tag = $this->tagRepository->find($id);
        $this->getUser()->removeTag($tag);

        $this->tagRepository->persist($tag);
        $this->tagRepository->flush();
        return $this->redirectToRoute('user.dashboard');
    }

    #[Route('/account', name: 'user.account')]
    public function account(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->save($user);
            return $this->redirectToRoute('user.account');
        }

        return $this->render('dashboard/account.html.twig',[
            'accountForm' => $form->createView()
        ]);
    }

    #[Route('/dashboard', name: 'user.dashboard')]
    public function dashboard(Request $request): Response
    {
        $form = $this->createForm(TagFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->handleTagForm($form);
        }

        return $this->render('dashboard/index.html.twig', [
            'tagForm' => $form->createView()
        ]);
    }

    private function handleTagForm(FormInterface $form) : RedirectResponse
    {
        $title = $form->get('title')->getData();
        $level = $form->get('level')->getData();

        $tag = $this->tagRepository->findByTitleAndLevel($title, $level);

        if(!$tag){
            $tag = new Tag();
            $tag->setTitle($title);
            $tag->setLevel($level);
            $tag->addUser($this->getUser());
        }else{
            if(!$this->getUser()->getTags()->contains($tag)) {
                $tag->addUser($this->getUser());
            }
        }

        $this->tagRepository->persist($tag);
        $this->tagRepository->flush();

        return $this->redirectToRoute('user.dashboard');
    }




}
