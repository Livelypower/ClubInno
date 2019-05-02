<?php

namespace App\Controller;

use App\Entity\Semester;
use App\Entity\Tag;
use App\Form\ActivityType;
use App\Form\NewSemesterType;
use App\Form\QueryUserType;
use App\Form\SetActiveSemesterForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Application;
use App\Entity\User;
use App\Entity\Activity;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
    public function listUsers(Request $request)
    {
        $user = new User();

        $form = $this->createForm(QueryUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $firstname = $user->getFirstName();
            $lastname = $user->getLastName();
            if ($firstname != null){
                if ($lastname == null){
                    $users = $this->getDoctrine()->getRepository(User::class)->findWhereFirstName($firstname);
                } elseif ($lastname != null){
                    $users = $this->getDoctrine()->getRepository(User::class)->findWhereFullName($firstname, $lastname);
                }
            } elseif ($lastname != null){
                $users =$this->getDoctrine()->getRepository(User::class)->findWhereLastName($lastname);
            } else {
                $users = $this->getDoctrine()->getRepository(User::class)->findAll();
            }
            return $this->render('admin/list_users.html.twig', [
                'users' => $users,
                'form' => $form->createView()
            ]);
        }

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/list_users.html.twig', [
            'users' => $users,
            'form' => $form->createView()
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
            unset($roles);
            $roles = [];
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
            unset($roles);
            $roles = [];
            array_push($roles, 'ROLE_TEACHER');

            $user->setRoles($roles);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/editUser/{id}/removeAdmin", requirements={"id": "\d+"}, name="admin_remove_user_admin")
     */
    public function removeAdmin($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        if (!in_array('ROLE_TEACHER', $roles)) {
            unset($roles);
            $roles = [];
            array_push($roles, 'ROLE_TEACHER');
            $user->setRoles($roles);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirectToRoute('admin_list_users');
    }

    /**
     * @Route("/admin/editUser/{id}/removeTeacher", requirements={"id": "\d+"}, name="admin_remove_user_teacher")
     */
    public function removeTeacher($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        if (!in_array('ROLE_USER', $roles)) {
            unset($roles);
            $roles = [];
            array_push($roles, 'ROLE_USER');
            $user->setRoles($roles);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

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
            //$file = $request->files->get('activity')['mainImage'];
            //$files = $request->files->get('activity')['files'];
            //$filenames = array();
            //$filename = null;
            $file = $form->get('mainImage')->getData();
            $files = $form->get('files')->getData();
            $filenames = array();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );
                foreach ($files as $fl){
                    $name = $this->generateUniqueFileName() . '.' . $fl->guessExtension();
                    array_push($filenames, $name);
                    $fl->move(
                        $this->getParameter('uploads_directory'),
                        $name
                    );
                }
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $activity = $form->getData();
            $activity->setMainImage($fileName);
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

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("admin/activity/{id}/edit", requirements={"id": "\d+"}, name="activity_edit")
     */
    public function editActivity(Request $request, Activity $activity)
    {
        if($activity->getMainImage() != null){
            $oldFile = new File($this->getParameter('uploads_directory').'/'.$activity->getMainImage());

            $activity->setMainImage($oldFile);
        }

        $oldFiles = array();

        if ($activity->getFiles() != null && !empty($activity->getFiles())) {
            foreach ($activity->getFiles() as $fln) {
                $fl =  new File($this->getParameter('uploads_directory').'/'.$fln);
                array_push($oldFiles, $fl);
            }
            $activity->setFiles($oldFiles);
        } else {
            $oldFiles = array();
        }

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('mainImage')->getData();
            if($file == null){
                $file = $oldFile;
            }
            $files = $form->get('files')->getData();
            if($files == null || empty($files)){
                $files = $oldFiles;
            }

            $filenames = array();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('uploads_directory'),
                    $fileName
                );
               foreach ($files as $fl){
                    $name = $this->generateUniqueFileName() . '.' . $fl->guessExtension();
                    array_push($filenames, $name);
                    $fl->move(
                        $this->getParameter('uploads_directory'),
                        $name
                    );
                }
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }


            $activity = $form->getData();
            $activity->setMainImage($fileName);
            $activity->setFiles($filenames);

            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('admin_list_activities');
        }

        return $this->render('activity/edit.html.twig', [
            'form' => $form->createView(),
            'image' => $activity->getMainImage()
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


        return $this->redirectToRoute('admin_semester');
    }
}
