<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BlogPost;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $blogPost = $this->getDoctrine()->getRepository(BlogPost::class)->findOneBy([],
            ['datetime' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'latest_blog_post' => $blogPost
        ]);
    }
}
