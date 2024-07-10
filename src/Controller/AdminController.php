<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Roles;
use App\Entity\Animaux;
use App\Form\AnimauxType;
use App\Entity\Nourriture;
use App\Form\AlimentationAnimauxType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
    public function alimentation(Request $request, EntityManagerInterface $em): Response
    {
        $nourriture = $em->getRepository(Nourriture::class)->findAll();

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

    #[Route('/admin/Services', name: 'app_pageServices')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageServices(): Response
    {
        return $this->render('admin/services.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/Horaires', name: 'app_pageHoraires')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageHoraires(): Response
    {
        return $this->render('admin/horaires.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/Habitats', name: 'app_pageHabitats')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageHabitats(): Response
    {
        return $this->render('admin/habitats.html.twig', [
            'controller_name' => 'AdminController',
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

    #[Route('/adminCRVet', name: 'app_pageCrVet')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageCrVet(): Response
    {
        return $this->render('admin/comptesRenduVeterinaire.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/adminCompteRendu', name: 'app_pageCompteRendu')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageCompteRendu(): Response
    {
        return $this->render('admin/compteRendus.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/adminRepas', name: 'app_pageRepas')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function pageRepas(): Response
    {
        return $this->render('admin/repasAnimaux.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
