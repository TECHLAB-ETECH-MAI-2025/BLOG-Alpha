<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleForm;
use App\Entity\Comment;
use App\Form\CommentForm;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    #[Route(name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository,Request $request, EntityManagerInterface $em): Response
    {
            $page = max(1, $request->query->getInt('page', 1));
            $limit = 2;
            $offset = ($page - 1) * $limit;
            $paginator = $articleRepository->ArticlePaginator($page, $limit);

                $query = $em->getRepository(Article::class)
                ->createQueryBuilder('a')
                ->setFirstResult(0)
                ->setMaxResults($limit)
                ->getQuery();
                
            $articles = $query->getResult();

    $totalArticles =$em->getRepository(Article::class)->count([]);
    $totalPages = ceil($totalArticles / $limit);
    // dd(totalPages);
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'totalPages' => $totalPages,
            'currentPage' => $page,
        ]);
    
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    // ajout champ commentaire sur la pages show 
    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Article $article ,Request $request , EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
		$comment->setArticle($article);

		// 	// Création du formulaire
			$form = $this->createForm(CommentForm::class, $comment);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
				$comment->setCreatedAt(new \DateTimeImmutable());
				$entityManager->persist($comment);
				$entityManager->flush();
				$this->addFlash('success', 'Votre commentaire a été publié avec succès !');

                return $this->redirectToRoute('app_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
            }

				return $this->render('article/show.html.twig', [
				'article' => $article,
				'commentForm' => $form->createView(),
			]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleForm::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        // return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
       return $this->render('article/index.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
   }
}
