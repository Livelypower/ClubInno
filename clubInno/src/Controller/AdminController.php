<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Application;

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

        return $this->render('admin/application_list.html.twig', [
            'applications' => $applications
        ]);
    }

    /**
     * @Route("/admin/applicationDetail/{id}", requirements={"id": "\d+"}, name="admin_application_show")
     */
    public function applicationDetail(Application $application){

        return $this->render('admin/application_show.html.twig');
    }

}
