<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Activity;
use App\Form\ActivityType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\Session;

class ActivityController extends AbstractController
{
    /**
     * @Route("/activity", name="activity")
     */
    public function index()
    {
        $roles = $this->getUser()->getRoles();

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)) {
            $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
            $user = $this->getUser();

            return $this->render('activity/index.html.twig', [
                'controller_name' => 'ActivityController',
                'tags' => $tags,
                'apiToken' => $user->getApiToken(),
            ]);
        }else{
            throw new AccessDeniedException();
        }
    }

    /**
     * @Route("/activity/{id}-{url}", requirements={"id": "\d+", "url"=".+"}, name="activity_show")
     */
    public function showActivity(Activity $activity, $url = "/activity"){
        $files = array();
        $imgs = array();

        foreach ($activity->getFiles() as $file){
            $pieces = explode(".", $file);
            $ext = $pieces[1];
            if($ext == "jpg" || $ext == "png" || $ext == "jpeg"){
                array_push($imgs, $file);
            }else{
                array_push($files, $file);
            }
        }

        return $this->render('activity/show.html.twig', [
            'activity' => $activity,
            'imgs' => $imgs,
            'files' => $files,
            'url' => $url
        ]);
    }

    /**
     * @Route("/activity/addBasket/{id}", requirements={"id": "\d+"}, name="activity_addbasket")
     */
    public function addToBasket(Activity $activity){
        $roles = $this->getUser()->getRoles();

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)) {
            if (!$this->get('session')->isStarted()) {
                $session = new Session();
                $session->start();
            } else {
                $session = $this->get('session');
            }

            if ($session->has("basket") && !empty($session->get('basket'))) {
                $basket = $session->get('basket');
                foreach ($basket as $item) {
                    if ($item->getId() != $activity->getId()) {
                        array_push($basket, $activity);
                        $session->set('basket', $basket);
                        break;
                    }
                }
            } else {
                $basket = array($activity);
                $session->set('basket', $basket);
            }


            return $this->redirectToRoute('activity');
        }else{
            throw new AccessDeniedException();
        }
    }

}
