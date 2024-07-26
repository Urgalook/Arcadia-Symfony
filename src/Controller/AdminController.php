<?php

namespace App\Controller;

use DateTime;
use App\Entity\Avis;
use App\Entity\Roles;
use App\Entity\Animaux;
use App\Entity\Habitat;
use App\Entity\HabitatVeterinaire;
use App\Entity\Horaire;
use App\Entity\Services;
use App\Form\AnimauxType;
use App\Form\HabitatType;
use App\Entity\Nourriture;
use App\Entity\Veterinaire;
use App\Form\ServicesType;
use App\Form\EditServiceType;
use Doctrine\DBAL\Connection;
use App\Form\AlimentationAnimauxType;
use App\Form\HabitatVeterinaireType;
use App\Form\VeterinaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function utilisateurs(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $role = $user->getRoles();
        $role = $role[0];
        
        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'utilisateur' => $user,
            'role' => $role
        ]);
    }

    #[Route('/admin/avis', name: 'app_pageAvis')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function avisEmploye(EntityManagerInterface $em): Response
    {
        $avis = $em->getRepository(Avis::class)->findAll();

        return $this->render('admin/avis.html.twig', [
            'controller_name' => 'EmployeController',
            'avis' => $avis,
        ]);
    }

    #[Route('/admin/avis/{id}/delete', name: 'app_deleteAvis')]
    public function deleteAvis($id, EntityManagerInterface $em): Response
    {
        $avis = $em->getRepository(Avis::class)->find($id);
        $em->remove($avis);
        $em->flush();

        $this->addFlash('success', 'Avis supprimé avec succès');

        return $this->redirectToRoute('app_pageAvis');
    }

    #[Route('/admin/avis/{id}/validate', name: 'app_validateAvis')]
    public function validateAvis($id, EntityManagerInterface $em): Response
    {
        $avis = $em->getRepository(Avis::class)->find($id);
        $avis->setValidation(true);
        $em->persist($avis);
        $em->flush();

        $this->addFlash('success', 'Avis validé avec succès');

        return $this->redirectToRoute('app_pageAvis');
    }

    #[Route('/admin/alimentation', name: 'app_pageAlimentation')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function alimentation(Request $request, EntityManagerInterface $em, Connection $connection): Response
    {
        // $nourriture = $em->getRepository(Nourriture::class)->findAll();
        $requete = 'select nou.id, nou.nourriture, nou.quantite, nou.date, ani.prenom from nourriture nou inner join animaux ani on ani.id = nou.animal_id';
        $stmt = $connection->prepare($requete);
        $execute = $stmt->executeQuery();
        $nourriture = $execute->fetchAllAssociative();
        $repas = new Nourriture();
        $formAlimentation = $this->createForm(AlimentationAnimauxType::class, $repas);
        $formAlimentation->handleRequest($request);

        if ($formAlimentation->isSubmitted() && $formAlimentation->isValid()) {
            $em->persist($repas);
            $em->flush();

            return $this->redirectToRoute('app_pageAlimentation');
        }
        return $this->render('admin/alimentation.html.twig', [
            'controller_name' => 'AdminController',
            'formAlimentation' => $formAlimentation->createView(),
            'nourriture' => $nourriture,
        ]);
    }

    #[Route('/admin/services', name: 'app_pageServices')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageServices(Request $request, EntityManagerInterface $em): Response
    {
        $services = $em->getRepository(Services::class)->findAll();

        $service = new Services();
        $formServices = $this->createForm(ServicesType::class, $service);
        $formServices->handleRequest($request);
        if ($formServices->isSubmitted() && $formServices->isValid()) {
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute('app_pageServices');

        }

        return $this->render('admin/services.html.twig', [
            'controller_name' => 'AdminController',
            'formServices' => $formServices->createView(),
            'service' => $service,
            'services' => $services
        ]);
    }

    #[Route('/admin/services/{id}/delete', name: 'app_deleteService')]
    public function deleteService($id, EntityManagerInterface $em): Response
    {
        $service = $em->getRepository(Services::class)->find($id);
        $em->remove($service);
        $em->flush();

        $this->addFlash('success', 'Service supprimé avec succès');

        return $this->redirectToRoute('app_pageServices');
    }

    #[Route('/admin/services/{id}/edit', name: 'app_editService')]
    public function editService($id, Request $request, EntityManagerInterface $em): Response
    {
        $service = $em->getRepository(Services::class)->find($id);

        if (!$service) {
            throw $this->createNotFoundException('Service not found');
        }

        $formServices = $this->createForm(ServicesType::class, $service);
        $formServices->handleRequest($request);

        if ($formServices->isSubmitted() && $formServices->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Service modifié avec succès');

            return $this->redirectToRoute('app_pageServices');
        }

        return $this->render('admin/editService.html.twig', [
            'controller_name' => 'AdminController',
            'service' => $service,
            'form' => $formServices->createView(),

        ]);
    }

    #[Route('/admin/Horaires', name: 'app_pageHoraires')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageHoraires(EntityManagerInterface $em): Response
    {
        $horaires = $em->getRepository(Horaire::class)->findAll();

        return $this->render('admin/horaires.html.twig', [
            'controller_name' => 'AdminController',
            'horaires' => $horaires,
        ]);
    }

    #[Route('/admin/habitats', name: 'app_pageHabitats')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageHabitats(Request $request, EntityManagerInterface $em): Response
    {
        $habitats = $em->getRepository(Habitat::class)->findAll();

        $habitat = new Habitat();
        $formHabitat = $this->createForm(HabitatType::class, $habitat);
        $formHabitat->handleRequest($request);
        if ($formHabitat->isSubmitted() && $formHabitat->isValid()) {
            $em->persist($habitat);
            $em->flush();

            return $this->redirectToRoute('app_pageHabitats');

        }

        return $this->render('admin/habitats.html.twig', [
            'controller_name' => 'AdminController',
            'formHabitat' => $formHabitat->createView(),
            'habitats' => $habitats,
            'habitat' => $habitat,
        ]);
    }

    #[Route('/admin/habitats/{id}/delete', name: 'app_deleteHabitat')]
    public function deleteHabitat($id, EntityManagerInterface $em): Response
    {
        $habitat = $em->getRepository(Habitat::class)->find($id);
        $em->remove($habitat);
        $em->flush();

        $this->addFlash('success', 'Habitat supprimé avec succès');

        return $this->redirectToRoute('app_pageHabitats');
    }

    #[Route('/admin/habitats/{id}/edit', name: 'app_editHabitat')]
    public function editHabitat($id, Request $request, EntityManagerInterface $em): Response
    {
        $habitat = $em->getRepository(Habitat::class)->find($id);

        if (!$habitat) {
            throw $this->createNotFoundException('Habitat not found');
        }

        $formHabitat = $this->createForm(HabitatType::class, $habitat);
        $formHabitat->handleRequest($request);

        if ($formHabitat->isSubmitted() && $formHabitat->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Habitat modifié avec succès');

            return $this->redirectToRoute('app_pageHabitats');
        }

        return $this->render('admin/editHabitat.html.twig', [
            'controller_name' => 'AdminController',
            'habitat' => $habitat,
            'formHabitat' => $formHabitat->createView(),

        ]);
    }

    #[Route('/admin/Animaux', name: 'app_pageAnimaux')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function animaux(Request $request, EntityManagerInterface $em): Response
    {

        $animaux = $em->getRepository(Animaux::class)->findAll();

        $animal = new Animaux();
        $formAnimaux = $this->createForm(AnimauxType::class, $animal);
        $formAnimaux->handleRequest($request);

        if ($formAnimaux->isSubmitted() && $formAnimaux->isValid()) {
            $em->persist($animal);
            $em->flush();

            return $this->redirectToRoute('app_pageAnimaux');
        }
        return $this->render('admin/animaux.html.twig', [
            'controller_name' => 'AdminController',
            'formAnimaux' => $formAnimaux->createView(),
            'animaux' => $animaux,
        ]);
    }

    #[Route('/admin/veterinaire', name: 'app_pageVeterinaire')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function veterinaire(Request $request, EntityManagerInterface $em, Connection $connection): Response
    {
        // dd($veterinaires);
        $requete = 'select vet.id, vet.etat, vet.nourriture, vet.grammage, vet.date, vet.remarque, ani.prenom from veterinaire vet inner join animaux ani on ani.id = vet.animal_id';
        $stmt = $connection->prepare($requete);
        $execute = $stmt->executeQuery();
        $veterinaires = $execute->fetchAllAssociative();
        $veterinaire = new Veterinaire();
        $formVeterinaire = $this->createForm(VeterinaireType::class, $veterinaire);
        $formVeterinaire->handleRequest($request);
        // dd($veterinaires, $veterinaire);
        if ($formVeterinaire->isSubmitted() && $formVeterinaire->isValid()) {
            $em->persist($veterinaire);
            $em->flush();

            $this->addFlash('success', 'Compte-rendu ajouté avec succès');
        
            return $this->redirectToRoute('app_pageVeterinaire');
        }

        return $this->render('admin/veterinaire.html.twig', [
            'controller_name' => 'AdminController',
            'formVeterinaire' => $formVeterinaire->createView(),
            'veterinaires' => $veterinaires,
            'veterinaire' => $veterinaire,
        ]);
    }

    #[Route('/admin/CompteRendu', name: 'app_pageCompteRendu')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function compteRendu(Connection $connection): Response
    {
        // dd($veterinaires);
        $requete = 'select vet.id, vet.etat, vet.nourriture, vet.grammage, vet.date, vet.remarque, ani.prenom from veterinaire vet inner join animaux ani on ani.id = vet.animal_id';
        $stmt = $connection->prepare($requete);
        $execute = $stmt->executeQuery();
        $veterinaires = $execute->fetchAllAssociative();

        return $this->render('admin/compteRendus.html.twig', [
            'controller_name' => 'AdminController',
            'veterinaires' => $veterinaires,
        ]);
    }

    #[Route('/adminRepas', name: 'app_pageRepas')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageRepas(Connection $connection): Response
    {
        $requete = 'select nou.id, nou.nourriture, nou.quantite, nou.date, ani.prenom from nourriture nou inner join animaux ani on ani.id = nou.animal_id';
        $stmt = $connection->prepare($requete);
        $execute = $stmt->executeQuery();
        $nourriture = $execute->fetchAllAssociative();

        return $this->render('admin/repasAnimaux.html.twig', [
            'controller_name' => 'AdminController',
            'nourriture' => $nourriture,
        ]);
    }

    #[Route('/adminHabitatVeterinaire', name: 'app_pageHabitatVeterinaire')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageHabitatVeterinaire(EntityManagerInterface $em, Request $request): Response
    {
        $commentaires = $em->getRepository(HabitatVeterinaire::class)->findAll();

        $commentaire = new HabitatVeterinaire();
        $formHabitatVet = $this->createForm(HabitatVeterinaireType::class, $commentaire);
        $formHabitatVet->handleRequest($request);

        if ($formHabitatVet->isSubmitted() && $formHabitatVet->isValid()) {
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('app_pageHabitatVeterinaire');
        }

        return $this->render('admin/habitatVeterinaire.html.twig', [
            'controller_name' => 'AdminController',
            'commentaire' => $commentaire,
            'commentaires' => $commentaires,
            'formHabitatVet' => $formHabitatVet->createView(),
        ]);
    }
}
