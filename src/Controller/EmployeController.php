<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Nourriture;
use Doctrine\DBAL\Connection;
use App\Form\AlimentationAnimauxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmployeController extends AbstractController
{
    #[Route('admin/employe', name: 'app_employe')]
    public function index(): Response
    {
        return $this->render('employe/index.html.twig', [
            'controller_name' => 'EmployeController',
        ]);
    }

    #[Route('admin/avis', name: 'avis_employe')]
    public function avisEmploye(EntityManagerInterface $em): Response
    {
        $avis = $em->getRepository(Avis::class)->findAll();

        return $this->render('admin/avis.html.twig', [
            'controller_name' => 'EmployeController',
            'avis' => $avis,
        ]);
    }

    #[Route('admin/alimentation', name: 'alimentation_employe')]
    public function alimentation(Request $request, EntityManagerInterface $em): Response
    {
        $repas = new Nourriture();
        $form = $this->createForm(AlimentationAnimauxType::class, $repas);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($repas);
            $em->flush();

            return $this->redirectToRoute('alimentation_employe');
        }

        return $this->render('admin/alimentation.html.twig', [
            'controller_name' => 'EmployeController',
            'formAlimentation' => $form->createView(),
        ]);
    }
}
