<?php

namespace App\Services\ApiServices;

use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;

class ApiService
{
    public function __construct(private AuthorRepository $authorRepository, private ArticleRepository $articleRepository) 
    {
    }

    public function getArticleById($id)
    {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            return 'Article with id ' . $id . ' not found';
        }

        $authors = $article->getAuthors()->map(function ($author) {
            return $author->getFirstName() . ' ' . $author->getLastName();
        });

        $authors = implode(', ', $authors->toArray());

        $response = [
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'text' => $article->getText(),
            'authors' => $authors,
        ];

        return $response;
    }


    public function getArticlesByAuthor($id)
    {
        $articles = $this->articleRepository->findArticlesByAuthor($id);

        if (empty($articles)) {
            return 'No articles found for author with id ' . $id;
        }

        $response = [];
        foreach ($articles as $article) {
            $response[] = [
                'id' => $article->getId(),
                'title' => $article->getTitle(),
                'text' => $article->getText(),
            ];
        }

        return $response;
    }

    public function getTopAuthors()
    {
        $authors = $this->authorRepository->findTopAuthorsLastWeek(3);

        if (!$authors) {
            return 'No authors found';
        }

        $response = [];
        foreach ($authors as $author) {
            $response[] = [
                'id' => $author['id'],
                'authorName' => $author['firstName'] . ' ' . $author['lastName'],
                'articlesCount' => $author['articlesCount'],
            ];
        }

        return $response;
    }
}
