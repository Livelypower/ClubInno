<?php

namespace App\Controller;

use App\Entity\Activity;
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

        $imgs = array();

        if($blogPost != null && !empty($blogPost->getFiles())){
            foreach ($blogPost->getFiles() as $file){
                $pieces = explode(".", $file);
                $ext = $pieces[1];
                if($ext == "jpg" || $ext == "png" || $ext == "jpeg"){
                    array_push($imgs, $file);
                }
            }
        }

        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'latest_blog_post' => $blogPost,
            'activities' => $activities,
            'imgs' => $imgs
        ]);
    }
}
