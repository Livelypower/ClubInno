<?php

namespace App\Controller;

use App\Entity\Semester;
use App\Entity\Tag;
use App\Form\ActivityType;
use App\Form\NewSemesterType;
use App\Form\SetActiveSemesterForm;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Application;
use App\Entity\User;
use App\Entity\Activity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\Session;use App\Form\TagType;use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/listApplications", name="admin_application_list")
     */
    public function listApplications()
    {
        $user = $this->getUser();
        return $this->render('admin/application_list.html.twig',[
            'apiToken' => $user->getApiToken()
        ]);
    }

    /**
     * @Route("/admin/applicationDetail/{id}", requirements={"id": "\d+"}, name="admin_application_show")
     */
    public function applicationDetail(Application $application)
    {

        return $this->render('admin/application_show.html.twig');
    }

    /**
     * @Route("/admin/listUsers/", name="admin_list_users")
     */
    public function listUsers()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/list_users.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/editUser/{id}/makeAdmin", requirements={"id": "\d+"}, name="admin_make_user_admin")
     */
    public function makeAdmin($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        if (!in_array('ROLE_AMDIN', $roles)) {
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
    public function makeTeacher($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        if (!in_array('ROLE_TEACHER', $roles)) {
            array_push($roles, 'ROLE_TEACHER');
            $user->setRoles($roles);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/editUser/{id}/makeAdmin", requirements={"id": "\d+"}, name="admin_remove_user_admin")
     */
    public function removeAdmin($id)
    {
        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/editUser/{id}/makeTeacher", requirements={"id": "\d+"}, name="admin_remove_user_teacher")
     */
    public function removeTeacher($id)
    {

        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/activities", name="admin_list_activities")
     */
    public function listActivities()
    {
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();
        $user = $this->getUser();

        return $this->render('admin/activity_list.html.twig', [
            'tags' => $tags,
            'apiToken' => $user->getApiToken(),
        ]);
    }

    /**
     * @Route("admin/activity/new", name="activity_new")
     */
    public function newActivity(Request $request)
    {
        $activity = new Activity();

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('activity')['mainImage'];
            $files = $request->files->get('activity')['files'];
            $filenames = array();
            $filename = null;

            $uploads_directory = $this->getParameter('uploads_directory');

            if ($file != null) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();

                foreach ($files as $fl) {
                    $name = md5(uniqid()) . '.' . $fl->guessExtension();
                    array_push($filenames, $name);
                    $fl->move(
                        $uploads_directory,
                        $name
                    );
                }

                $file->move(
                    $uploads_directory,
                    $filename
                );
            }


            $activity = $form->getData();
            $activity->setMainImage($filename);
            $activity->setFiles($filenames);

            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('admin_list_activities');
        }

        return $this->render('activity/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("admin/activity/{id}/edit", requirements={"id": "\d+"}, name="activity_edit")
     */
    public function editActivity(Request $request, Activity $activity)
    {
        $filename = $activity->getMainImage();
        $filenames = $activity->getFiles();

        $files = array();
        if ($filename != null) {
            $activity->setMainImage($this->getParameter('uploads_directory') . '/' . $filename);
        }

        if ($filenames != null && !empty($filenames)) {
            foreach ($filenames as $fln) {
                $fl = $this->getParameter('uploads_directory') . '/' . $fln;
                array_push($files, $fl);
            }
            $activity->setFiles($files);
        } else {
            $filenames = array();
        }

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('activity')['mainImage'];
            $files = $request->files->get('activity')['files'];
            $uploads_directory = $this->getParameter('uploads_directory');
            if ($file != null) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $uploads_directory,
                    $filename
                );
            }

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

            $activity = $form->getData();
            $activity->setMainImage($filename);
            $activity->setFiles($filenames);

            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('admin_list_activities');
        }

        return $this->render('activity/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/activity/{id}/delete", requirements={"id": "\d+"}, name="activity_delete")
     */
    public function deleteActivity(Activity $activity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($activity);
        $em->flush();

        return $this->redirectToRoute('admin_list_activities');
    }

    /**
     * @Route("admin/activity/{id}/toggle", requirements={"id": "\d+"}, name="activity_toggle")
     */
    public function toggleActivity(Activity $activity)
    {
        if ($activity->getActive() == 1){
            $activity->setActive(0);
        } else {
            $activity->setActive(1);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($activity);
        $em->flush();

        return $this->redirectToRoute('admin_list_activities');
    }

    /**
     * @Route("admin/semester/new", name="admin_semester_new")
     */
    public function newSemester(Request $request)
    {
        $semester = new Semester();
        $form = $this->createForm(NewSemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $semester = $form->getData();
            $semester->setActive(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($semester);
            $em->flush();

            return $this->redirectToRoute('admin_semester');
        }
        return $this->render('semester/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/semester/edit/{id}", name="admin_semester_edit")
     */
    public function editSemester(Semester $semester, Request $request){
        $form = $this->createForm(NewSemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $semester = $form->getData();
            $semester->setActive(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($semester);
            $em->flush();

            return $this->redirectToRoute('admin_semester');
        }
        return $this->render('semester/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/semester/delete/{id}", name="admin_semester_delete")
     */
    public function deleteSemester(Semester $semester){
        $em = $this->getDoctrine()->getManager();
        $em->remove($semester);
        $em->flush();

        return $this->redirectToRoute('admin_semester');
    }

    /**
     * @Route("admin/semester/setactive", name="admin_semester")
     */
    public function semester(Request $request)
    {
        $years = $this->getDoctrine()->getRepository(Semester::class)->findAll();

        return $this->render('semester/index.html.twig', [
            'semesters' => $years
        ]);
    }

    /**
     * @Route("admin/semester/setactive/{id}/", requirements={"id": "\d+"}, name="admin_set_active_semester")
     */
    public function setActiveSemester(int $id)
    {
        $activeSemester = $this->getDoctrine()->getRepository(Semester::class)->find($id);
        $allSemesters = $this->getDoctrine()->getRepository(Semester::class)->findAll();
        $activitiesToSetActive = $this->getDoctrine()->getRepository(Activity::class)->findBy(['semester' => $activeSemester]);
        $activitiesToSetUnActive = $this->getDoctrine()->getRepository(Activity::class)->findWhereSemesterNot($activeSemester);

        foreach ($activitiesToSetActive as $activity){
            $activity->setActive(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();
        }

        foreach ($activitiesToSetUnActive as $activity){
            $activity->setActive(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();
        }

        foreach($allSemesters as $semester){
            if($semester->getId() != $activeSemester->getId()){
                $semester->setActive(false);
            }else{
                $activeSemester->setActive(true);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($semester);
            $em->flush();
        }


        return $this->redirectToRoute('admin_list_activities');
    }
}
