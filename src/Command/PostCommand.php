<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:load-posts')]
class PostCommand extends Command
{
    private $client;
    private $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager) {
        $this->client = $client;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Load posts start');

        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/posts'
        );
        $posts = $response->toArray();

        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/users'
        );
        $users = [];

        foreach ($response->toArray() as $user) {
            $users[$user['id']] = $user;
        }

        $count = 0;

        foreach ($posts as $post) {
            $params = ['userId' => $post['userId'], 'title' => $post['title'], 'body' => $post['body']];
            $userId = $post['userId'];
            $postModel = $this->entityManager->getRepository(Post::class)->findOneBy($params);
            $authorModel = $this->entityManager->getRepository(Author::class)->findOneBy(['id' => $userId]);
            if (!$authorModel) {
                $authorModel = new Author();
                $authorModel->setName($users[$userId]['name']);
                $this->entityManager->persist($authorModel);
                $this->entityManager->flush();
            }
            if (!$postModel) {
                $postModel = new Post();
                $postModel->setUserId($authorModel);
                $postModel->setBody($post['body']);
                $postModel->setTitle($post['title']);
                $this->entityManager->persist($postModel);
                $this->entityManager->flush();
                $count++;
            }
        }
        $output->writeln('Load posts end');
        $output->writeln('Total posts created: '.$count);

        return Command::SUCCESS;
    }
}