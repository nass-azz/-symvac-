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

            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/new.html.twig', [
            'form' => $form,
        ]);
    }
}