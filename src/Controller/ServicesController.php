<?php

namespace App\Controller;

use App\Entity\Services;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServicesController extends AbstractController
{
    #[Route('/services', name: 'app_services')]
    public function index(EntityManagerInterface $em): Response
    {
        $services = $em->getRepository(Services::class)->findAll();
        return $this->render('services/index.html.twig', [
            'controller_name' => 'ServicesController',
            'services' => $services,
        ]);
    }
}

