<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Activity;
use App\Form\ActivityType;
use Symfony\Component\HttpFoundation\File\File;



class ActivityController extends AbstractController
{
    /**
     * @Route("/activity", name="activity")
     */
    public function index()
    {
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();

        return $this->render('activity/index.html.twig', [
            'controller_name' => 'ActivityController',
            'activities' => $activities
        ]);
    }

    /**
     * @Route("/activity/{id}", requirements={"id": "\d+"}, name="activity_show")
     */
    public function showActivity(Activity $activity){
        return $this->render('activity/show.html.twig', [
            'activity' => $activity
        ]);
    }

    /**
     * @Route("/activity/new", name="activity_new")
     */
    public function newActivity(Request $request)
    {
        $activity = new Activity();

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('activity')['mainImage'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $uploads_directory,
                $filename
            );

            $activity = $form->getData();
            $activity->setMainImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('activity');
        }

        return $this->render('activity/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/activity/{id}/edit", requirements={"id": "\d+"}, name="activity_edit")
     */
    public function editActivity(Request $request, Activity $activity){

        $filename = $activity->getMainImage();
        if($filename != null){
            $activity->setMainImage($this->getParameter('uploads_directory').'/'. $filename);
        }
        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('activity')['mainImage'];
            $uploads_directory = $this->getParameter('uploads_directory');
            if($file != null){
                $filename = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $uploads_directory,
                    $filename
                );
            }


            $activity = $form->getData();
            $activity->setMainImage($filename);

            $em = $this->getDoctrine()->getManager();
            $em->persist($activity);
            $em->flush();

            return $this->redirectToRoute('activity');
        }

        return $this->render('activity/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
