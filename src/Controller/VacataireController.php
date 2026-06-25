<?php

namespace App\Controller;

use App\Entity\Vacataire;
use App\Form\VacataireType;
use App\Repository\VacataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VacataireController extends AbstractController
{
    // 1. AJOUT DE LA MÉTHODE POUR LA LISTE (Route: app_vacataire)
    #[Route('/vacataire', name: 'app_vacataire', methods: ['GET'])]
    public function index(VacataireRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $data = $repository->findAll();

        $vacataires = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), /* numéro de la page en cours */
            10 /* 10 vacataires par page */
        );

        return $this->render('pages/vacataire/index.html.twig', [
            'vacataires' => $vacataires,
        ]);
    }

    // 2. CONSERVATION DE TA MÉTHODE POUR LA CRÉATION
    #[Route('/vacataire/nouveau', name: 'vacataire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $vacataire = new Vacataire();
        $form = $this->createForm(VacataireType::class, $vacataire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $vacataire = $form->getData();

            $manager->persist($vacataire);
            $manager->flush();

            $this->addFlash(
                'success',
                'Vos changements ont été enregistrés !'
            );

            // Maintenant que la méthode index existe au-dessus, cette redirection va fonctionner !
            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/vacataire/edition/{id}', name: 'vacataire_edit', methods: ['GET', 'POST'])]
    public function edit(Vacataire $vacataire, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(VacataireType::class, $vacataire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush(); 

            $this->addFlash(
                'success',
                'Le vacataire a été modifié avec succès !'
            );

            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/vacataire/suppression/{id}', name: 'vacataire_delete', methods: ['GET'])]
    public function delete(Vacataire $vacataire, EntityManagerInterface $manager): Response
    {
        $manager->remove($vacataire);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le vacataire a été supprimé avec succès !'
        );

        return $this->redirectToRoute('app_vacataire');
    }
}