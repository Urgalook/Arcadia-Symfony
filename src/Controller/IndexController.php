<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Horaire;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function avis(Connection $connection): Response
    {   

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
        ]);
    }
}
