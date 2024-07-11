<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Horaire;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function avis(Connection $connection): Response
    {   
        $user = $this->getUser();
        // $role = $user->getRoles();

        $requete = "SELECT * FROM `avis` WHERE validation = 1;";
        $stmt = $connection->prepare($requete);
        $execute = $stmt->executeQuery();
        $avis = $execute->fetchAllAssociative();

        $requete = "SELECT * FROM horaire ORDER BY id ASC";
        $stmt = $connection->prepare($requete);
        $execute = $stmt->executeQuery();
        $horaires = $execute->fetchAllAssociative();

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'avis' => $avis,
            'horaire' => $horaires,
            'user' => $user,
            // 'role' => $role,
        ]);
    }

    #[Route('/avis', name: 'app_avis')]
    public function avisClient(Request $request, EntityManagerInterface $em): Response
    {  

        $avisClient = new Avis();
        $formAvis = $this->createForm(AvisType::class, $avisClient);
        $formAvis->handleRequest($request);

        if ($formAvis->isSubmitted() && $formAvis->isValid()) {
            $em->persist($avisClient);
            $em->flush();

            $this->addFlash('success', 'Message envoyé !');

            return $this->redirectToRoute('app_avis');
        }
            return $this->render('index/avisClient.html.twig', [
                'controller_name' => 'IndexController',
                'formAvis' => $formAvis->createView(),
                'avis' => $avisClient,
            ]);
    }
}
