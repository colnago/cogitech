<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;

class HomepageController extends AbstractController
{
    public $client;

    public function __construct(HttpClientInterface $client) {
        $this->client = $client;
    }

    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}
