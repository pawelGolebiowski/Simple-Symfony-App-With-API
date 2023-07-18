<?php

namespace App\Controller;

use App\Services\ApiServices\ApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    public function __construct(private ApiService $articleService)
    {
    }

    #[Route('/api/articles/{id}', name: 'article_by_id', methods: ['GET'])]
    public function getArticleById($id): Response
    {
        $response = $this->articleService->getArticleById($id);

        return new Response(json_encode($response), 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/api/articles/author/{id}', name: 'articles_by_author', methods: ['GET'])]
    public function getArticlesByAuthor($id): Response
    {
        $response = $this->articleService->getArticlesByAuthor($id);

        return new Response(json_encode($response), 200, ['Content-Type' => 'application/json']);
    }

    #[Route('/api/article/top-authors', name: 'article_top_authors', methods: ['GET'])]
    public function getTopAuthors(): JsonResponse
    {
        $response = $this->articleService->getTopAuthors();

        return new JsonResponse($response);
    }
}
