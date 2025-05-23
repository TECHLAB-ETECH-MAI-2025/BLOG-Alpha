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


final class HomePageController extends AbstractController
{
     #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('HomePage.html.twig');
    }

     #[Route('/article', name: 'article')]
    public function ShowArticle():Response
    {
        return $this->render('article/index.html.twig');
    }
}