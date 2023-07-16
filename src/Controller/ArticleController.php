<?php

namespace App\Controller;

use App\Form\ArticleType;
use App\Services\ArticleServices\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'article_index')]
    public function index(ArticleService $articleService): Response
    {
        $articles = $articleService->getAllArticles();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/new', name: 'article_new')]
    public function new(Request $request, ArticleService $articleService): Response
    {
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleService->createArticle($form->getData());

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'article_show')]
    public function show($id, ArticleService $articleService): Response
    {
        $article = $articleService->getArticleById($id);

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/edit/{id}', name: 'article_edit')]
    public function edit(Request $request, ArticleService $articleService, int $id): Response
    {
        $article = $articleService->getArticleById($id);

        if (!$article) {
            throw $this->createNotFoundException('No article found for id '.$id);
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleService->updateArticle($id, $form->getData());

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}