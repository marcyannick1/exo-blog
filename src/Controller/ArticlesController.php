<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/articles')]
class ArticlesController extends AbstractController
{
    #[Route('/', name: 'app_articles_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('articles/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_articles_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDateDeCreation(new \DateTime());
            if ($article->getEtat() == "publié") {
                $article->setDateDeParution(new \DateTime());
            }
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        $commentaires = $article->getCommentaires()->toArray();
        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'commentaires' => $commentaires
        ]);
    }

    #[Route('/{id}/edit', name: 'app_articles_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($article->getEtat() == "publié") {
                $article->setDateDeParution(new \DateTime());
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('articles/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/comment', name: 'app_articles_comment')]
    public function comment(Request $request, Article $article, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $comment = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $comment->setDateDePublication(new \DateTime());
            $comment->setAuteur($user);
            $comment->setArticle($article);
            $comment->setEtat('activé');

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('app_articles_index');
        }

        return $this->render('articles/comment.html.twig', [
            'form' => $form,
            'article' => $article,
        ]);
    }

    #[Route('/{id}', name: 'app_articles_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_articles_index', [], Response::HTTP_SEE_OTHER);
    }
}
