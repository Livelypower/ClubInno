<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Application;
use App\Entity\User;
use App\Entity\Activity;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/listApplications", name="admin_application_list")
     */
    public function listApplications(){

        $applications = $this->getDoctrine()->getRepository(Application::class)->findAll();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();

        $jsonUsers = array();
        $jsonActi = array();

        foreach ($users as $user){
            array_push($jsonUsers,$user->jsonSerialize());
        }

        foreach ($activities as $acti){
            array_push($jsonActi,$acti->jsonSerialize());
        }

        return $this->render('admin/application_list.html.twig', [
            'applications' => $applications,
            'users' => $jsonUsers,
        ]);
    }

    /**
     * @Route("/admin/applicationDetail/{id}", requirements={"id": "\d+"}, name="admin_application_show")
     */
    public function applicationDetail(Application $application){

        return $this->render('admin/application_show.html.twig');
    }

}
