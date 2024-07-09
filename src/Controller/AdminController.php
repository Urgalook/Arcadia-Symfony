<?php

namespace App\Controller;

use App\Entity\Roles;
use Doctrine\ORM\EntityManagerInterface;
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
}
