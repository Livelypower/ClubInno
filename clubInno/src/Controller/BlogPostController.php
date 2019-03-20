<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BlogPostController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        $blogPosts = $this->getDoctrine()->getRepository(BlogPost::class)->findAll();

        return $this->render('blog_post/index.html.twig', [
            'controller_name' => 'BlogPostController',
            'blogPosts' => $blogPosts
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_new")
     */
    public function newBlogPost(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $blogPost = new BlogPost();

        $form = $this->createForm(BlogPostType::class, $blogPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogPost = $form->getData();
            $blogPost->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($blogPost);
            $em->flush();

            return $this->redirectToRoute('blog');
        }

        return $this->render('blog_post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/{id}", requirements={"id": "\d+"}, name="blog_show")
     */
    public function showBlogPost(BlogPost $blogPost){
        return $this->render('blog_post/show.html.twig', [
            'post' => $blogPost,
        ]);
    }
}
