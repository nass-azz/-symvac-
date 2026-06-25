<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\VacataireRepository;
use Symfony\Component\Routing\Attribute\Route;

final class VacataireController extends AbstractController
{
    #[Route('/vacataire', name: 'app_vacataire')]
   

public function index(VacataireRepository $repository): Response
{
    $vacataires = $repository->findAll();

    return $this->render('pages/vacataire/index.html.twig', [
        'vacataires' => $vacataires,
    ]);
}
}


