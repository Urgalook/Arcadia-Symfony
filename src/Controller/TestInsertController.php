<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestInsertController extends AbstractController
{
    #[Route('/test/insert', name: 'app_test_insert')]
    public function index(): Response
    {
        return $this->render('test_insert/index.html.twig', [
            'controller_name' => 'TestInsertController',
        ]);
    }

    #[Route('/test/insertbis', name: 'app_test_insertbis')]
    public function indexbis(Connection $connection, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $password = "tutu123";
        $user = new User();
        $hash = $userPasswordHasher->hashPassword($user, $password);
        $user->setEmail('totu@tutu.fr');
        $user->setPassword($hash);
        $em->persist($user);
        $em->flush();
        return $this->json([$user]);
    }
}
