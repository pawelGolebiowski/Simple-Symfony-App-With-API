<?php

namespace App\Services\ArticleServices;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class ArticleService
{
    private $entityManager;
    private $articleRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    public function getAllArticles(): array
    {
        return $this->articleRepository->findAll();
    }

    public function getArticleById(int $id): ?Article
    {
        return $this->articleRepository->find($id);
    }

    public function createArticle(Article $article): void
    {
        $this->entityManager->persist($article);
        $this->entityManager->flush();
    }

    public function updateArticle(int $id, Article $articleData): void
    {
        $article = $this->getArticleById($id);

        if (!$article) {
            throw new \Exception('Article not found');
        }

        $article->setTitle($articleData->getTitle());
        $article->setText($articleData->getText());

        $authors = $articleData->getAuthors();
        foreach ($authors as $author) {
            $article->addAuthor($author);
        }

        $this->entityManager->flush();
    }

}
