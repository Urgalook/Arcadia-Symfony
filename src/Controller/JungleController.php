<?php

namespace App\Controller;

use App\Entity\Animaux;
use App\Entity\Habitat;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JungleController extends AbstractController
{
    #[Route('/habitats', name: 'app_habitats')]
    public function habitats(): Response
    {
        return $this->render('habitats/index.html.twig', [
            'controller_name' => 'JungleController',
        ]);
    }


    #[Route('/habitats/{habitat}', name: 'app_jungle')]
    public function habitat($habitat, EntityManagerInterface $em, Connection $connection): Response
    {
        $nom = $habitat;
        $habitat = $em->getRepository(Habitat::class)->findOneBy(['Nom' => $nom]);
        // dd($habitat);
        $habitatid = $habitat->getId();
        $requete = "select ani.prenom, esp.nom as nomEspece, hab.nom as nomHabitat from animaux ani inner join habitat hab on hab.id = ani.habitat_id inner join espece esp on esp.id = ani.espece_id where hab.id = :habitatid";
        $stmt = $connection->prepare($requete);
        $stmt->bindValue('habitatid', $habitatid);
        $execute = $stmt->executeQuery();
        $allAnimaux = $execute->fetchAllAssociative();

        $animauxParHabitat = [];

        foreach ($allAnimaux as $animal) {
            $espece = $animal['nomEspece'];
            $prenom = $animal['prenom'];

        // Initialiser l'entrée pour ce habitat s'il n'existe pas encore
        if (!isset($animauxParHabitat[$espece])) {
            $animauxParHabitat[$espece] = [
                'espece' => $espece,
                'prenoms' => []
            ];
        }

            // Ajouter le prénom à la liste des prénoms pour ce habitat
            $animauxParHabitat[$espece]['prenoms'][] = $prenom;
        }     
        // dd($animauxParHabitat);

        return $this->render('jungle/index.html.twig', [
            'controller_name' => 'JungleController',
            'habitat' => $habitat,
            'allAnimaux' => $animauxParHabitat,
            'habitatName' => $nom,
        ]);
    }


}
