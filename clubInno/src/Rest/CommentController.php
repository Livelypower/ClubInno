<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21/03/2019
 * Time: 11:10
 */

namespace App\Rest;


use App\Entity\Activity;
use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class CommentController extends AbstractFOSRestController
{
    /**
     * Creates an Comment resource
     * @Rest\Post("/comment/add")
     * @param Request $request
     * @return View
     */
    public function postComment(Request $request): View
    {
        $comment = new Comment();
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('userId'));
        if ($request->get('blogPost') != null){
            $blogPost = $this->getDoctrine()->getRepository(BlogPost::class)->find($request->get('blogPost'));
            $comment->setBlogPost($blogPost);
        }

        $comment->setBody($request->get('body'));
        $comment->setDatetime(new \DateTime('now'));
        $comment->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($comment, Response::HTTP_CREATED);
    }

    /**
     * Retrieves an Comment resource
     * @Rest\Get("/comment/{commentId}")
     */
    public function getComment(int $commentId): View
    {
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($comment, Response::HTTP_OK);
    }

    /**
     * @Rest\Patch("/comment/update/{commentId}")
     */
    public function updateComment(Request $request, int $commentId): View
    {
        if($request->get('comment') != null && $request->get('comment') != ""){
            $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);
            $comment->setBody($request->get('comment'));

            $em = $this->getDoctrine()->getManager();
            $comment->setDatetime(new \DateTime('now'));
            $em->persist($comment);
            $em->flush();

            return View::create($comment, Response::HTTP_OK);
        }
    }

    /**
     * Retrieves an Comment resource
     * @Rest\Delete("/comment/delete/{commentId}")
     */
    public function deleteComment(int $commentId): View
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $this->getDoctrine()->getRepository(Comment::class)->find($commentId);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        $em->remove($comment);
        $em->flush();

        return View::create($comment, Response::HTTP_OK);
    }



    /**
     * Retrieves all Comment resources belonging to a blogpost
     * @Rest\Get("/blog/{blogPostId}/comments")
     */
    public function getComments(int $blogPostId): View
    {
        //$blogPost = $this->getDoctrine()->getRepository(BlogPost::class)->find($blogPostId);
        $comments = $this->getDoctrine()->getRepository(BlogPost::class)->find($blogPostId);
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($comments, Response::HTTP_OK);
    }

    /**
     * Retrieves all Activity Objects
     * @Rest\Get("/admin/activities")
     */
    public function getActis(): View
    {
        //$blogPost = $this->getDoctrine()->getRepository(BlogPost::class)->find($blogPostId);
        $actis = $this->getDoctrine()->getRepository(Activity::class)->findAll();
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($actis, Response::HTTP_OK);
    }

    /**
     * Retrieves all User Objects
     * @Rest\Get("/admin/users")
     */
    public function getUsers(Request $request): View
    {
        $oris = $request->get('orientations');
        $allUsers = $this->getDoctrine()->getRepository(User::class)->findAll();
        $users = array();

        if(empty($oris)){
            $users = $allUsers;
        }else{
            foreach($allUsers as $user){
                if(in_array($user->getOrientation(), $oris)){
                    array_push($users, $user);
                }
            }
        }



        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($users, Response::HTTP_OK);
    }



}