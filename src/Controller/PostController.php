<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        $posts = $this->entityManager->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/posts/delete/{id}', methods: ['GET', 'DELETE'],  name: 'app_delete_post')]
    public function delete($id): Response
    {
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException('No post found for id '.$id);
        }
        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_posts');
    }
}