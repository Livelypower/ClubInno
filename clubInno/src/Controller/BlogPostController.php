<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Form\BlogPostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('ROLE_TEACHER');
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

    /**
     * @Route("/blog/{id}/delete", requirements={"id": "\d+"}, name="blog_delete")
     */
    public function deleteBlog(BlogPost $blogPost){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();

        if(in_array('ROLE_ADMIN', $user->getRoles()) || $user->getId() == $blogPost->getUser()->getId()){
            $em = $this->getDoctrine()->getManager();
            $comments = $blogPost->getComments();
            foreach($comments as $comment){
                $em->remove($comment);
            }
            $em->remove($blogPost);
            $em->flush();

            return $this->redirectToRoute('blog');
        }else{
            throw new AccessDeniedException();
        }

    }

    /**
     * @Route("/blog/{id}/edit", requirements={"id": "\d+"}, name="blog_edit")
     */
    public function editBlog(Request $request, BlogPost $blogPost){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();

        if(in_array('ROLE_ADMIN', $user->getRoles()) || $user == $blogPost->getUser()) {
            $filenames = $blogPost->getFiles();

            $files = array();

            if ($filenames != null && !empty($filenames)) {
                foreach ($filenames as $fln) {
                    $fl = $this->getParameter('uploads_directory') . '/' . $fln;
                    array_push($files, $fl);
                }
                $blogPost->setFiles($files);
            } else {
                $filenames = array();
            }

            $form = $this->createForm(BlogPostType::class, $blogPost);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $files = $request->files->get('blog_post')['files'];
                $uploads_directory = $this->getParameter('uploads_directory');

                if ($files != null && !empty($files)) {
                    foreach ($files as $f) {
                        $fn = md5(uniqid()) . '.' . $f->guessExtension();
                        array_push($filenames, $fn);
                        $f->move(
                            $uploads_directory,
                            $fn
                        );
                    }
                }

                $blogPost = $form->getData();
                $blogPost->setUser($user);
                $blogPost->setFiles($filenames);
                $blogPost->setDateTime(new \DateTime('now'));

                $em = $this->getDoctrine()->getManager();
                $em->persist($blogPost);
                $em->flush();

                return $this->redirectToRoute('blog');
            }

            return $this->render('blog_post/edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }else{
            throw new AccessDeniedException();
        }
    }


}
