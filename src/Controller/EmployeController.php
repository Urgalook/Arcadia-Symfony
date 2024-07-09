<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployeController extends AbstractController
{
    #[Route('admin/employe', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }

    #[Route('admin/employe/avis', name: 'avis_employe')]
    public function avisEmploye(): Response
    {
        return $this->render('employe/avis.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }
}
