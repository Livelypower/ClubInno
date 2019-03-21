<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'files' => $files
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
            $files = $request->files->get('activity')['files'];
            $filenames = array();
            $filename = null;

            $uploads_directory = $this->getParameter('uploads_directory');

            if($file != null){
                $filename = md5(uniqid()) . '.' . $file->guessExtension();

                foreach($files as $fl){
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
        $filenames = $activity->getFiles();

        $files = array();
        if($filename != null){
            $activity->setMainImage($this->getParameter('uploads_directory').'/'. $filename);
        }

        if($filenames != null && !empty($filenames)){
            foreach($filenames as $fln){
                $fl = $this->getParameter('uploads_directory').'/'. $fln;
                array_push($files, $fl);
            }
            $activity->setFiles($files);
        }else{
            $filenames = array();
        }

        $form = $this->createForm(ActivityType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('activity')['mainImage'];
            $files = $request->files->get('activity')['files'];
            $uploads_directory = $this->getParameter('uploads_directory');
            if($file != null){
                $filename = md5(uniqid()) . '.' . $file->guessExtension();

                $file->move(
                    $uploads_directory,
                    $filename
                );
            }

            if($files != null && !empty($files)){
                foreach ($files as $f){
                    $fn =  md5(uniqid()) . '.' . $f->guessExtension();
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

            return $this->redirectToRoute('activity');
        }

        return $this->render('activity/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/activity/{id}/delete", requirements={"id": "\d+"}, name="activity_delete")
     */
    public function deleteActivity(Activity $activity){
        $em = $this->getDoctrine()->getManager();
        $em->remove($activity);
        $em->flush();

        return $this->redirectToRoute('activity');
    }

    /**
     * @Route("/activity/addBasket/{id}", requirements={"id": "\d+"}, name="activity_addbasket")
     */
    public function addToBasket(Activity $activity){
        if($session->has("basket")){
            $basket = $session->get('basket');
            array_push($basket, $activity);
            $session->set('name', $basket);
        }else{
            $basket = array($activity);
            $session->set('name', $basket);
        }

        return $this->redirectToRoute('activity');
    }
}
