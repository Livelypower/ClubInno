<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Comment;
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
    public function newBlogPost(Request $request, \Swift_Mailer $mailer)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $blogPost = new BlogPost();

        $form = $this->createForm(BlogPostType::class, $blogPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files = $request->files->get('blog_post')['files'];
            $filenames = array();
            $uploads_directory = $this->getParameter('uploads_directory');

            foreach($files as $file){
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                array_push($filenames, $filename);
                $file->move(
                    $uploads_directory,
                    $filename
                );
            }


            $blogPost = $form->getData();
            $blogPost->setUser($user);
            $blogPost->setFiles($filenames);
            $blogPost->setDateTime(new \DateTime('now'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($blogPost);
            $em->flush();

            $message = (new \Swift_Message('New Blog Post!'))
                ->setFrom('kasumiiwamoto69@gmail.com')
                ->setTo('celpynenborg@gmail.com')
                ->setBody(
                    $this->renderView('emails/new_blog_post.html.twig', [
                        'post' => $blogPost
                    ])
                );

            $mailer->send($message);

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
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findBy(['blogPost' => $blogPost]);
        $files = array();
        $imgs = array();

        if(!empty($blogPost->getFiles())){
            foreach ($blogPost->getFiles() as $file){
                $pieces = explode(".", $file);
                $ext = $pieces[1];
                if($ext == "jpg" || $ext == "png" || $ext == "jpeg"){
                    array_push($imgs, $file);
                }else{
                    array_push($files, $file);
                }
            }
        }


        return $this->render('blog_post/show.html.twig', [
            'post' => $blogPost,
            'comments' => $comments,
            'imgs' => $imgs,
            'files' => $files
        ]);
    }


}
