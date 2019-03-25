<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
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
    public function listApplications(SerializerInterface $serializer){

        $applications = $this->getDoctrine()->getRepository(Application::class)->findAll();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();

        $jsonUsers = array();
        $jsonActi = array();


        foreach ($users as $user){
            array_push($jsonUsers, $serializer->serialize($user, 'json', [
                'circular_reference_handler' => function ($user) {
                    return $user->getId();
                }
            ]));
        }
        foreach ($activities as $acti){
            array_push($jsonActi, $serializer->serialize($acti, 'json', [
                'circular_reference_handler' => function ($acti) {
                    return $acti->getId();
                }
            ]));
        }



        return $this->render('admin/application_list.html.twig', [
            'applications' => $applications,
            'users' => $jsonUsers,
            'activities' => $jsonActi
        ]);
    }

    /**
     * @Route("/admin/applicationDetail/{id}", requirements={"id": "\d+"}, name="admin_application_show")
     */
    public function applicationDetail(Application $application){

        return $this->render('admin/application_show.html.twig');
    }

    /**
     * @Route("/admin/listUsers/", name="admin_list_users")
     */
    public function listUsers(){
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/list_users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/editUser/{id}/makeAdmin", requirements={"id": "\d+"}, name="admin_make_user_admin")
     */
    public function makeAdmin($id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        if (!in_array('ROLE_AMDIN', $roles)){
            array_push($roles, 'ROLE_ADMIN');
            $user->setRoles($roles);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/editUser/{id}/makeTeacher", requirements={"id": "\d+"}, name="admin_make_user_teacher")
     */
    public function makeTeacher($id){
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        if (!in_array('ROLE_TEACHER', $roles)){
            array_push($roles, 'ROLE_TEACHER');
            $user->setRoles($roles);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/editUser/{id}/makeTeacher", requirements={"id": "\d+"}, name="admin_remove_user_admin")
     */
    public function removeAdmin($id){
        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/editUser/{id}/makeTeacher", requirements={"id": "\d+"}, name="admin_remove_user_teacher")
     */
    public function removeTeacher($id){

        return $this->redirectToRoute('admin_list_users');
    }


}
