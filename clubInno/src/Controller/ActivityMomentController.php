<?php

namespace App\Controller;

use App\Entity\ActivityMoment;
use App\Form\ActivityMomentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ActivityMomentController extends AbstractController
{
    /**
     * @Route("/activity/moment", name="activity_moment")
     */
    public function index()
    {
        return $this->render('activity_moment/index.html.twig', [
            'controller_name' => 'ActivityMomentController',
        ]);
    }

    /**
     * @Route("/activity/moment/new", name="activity_moment_new")
     */
    public function newActivityMoment(Request $request){
        $activityMoment = new ActivityMoment();
        $form = $this->createForm(ActivityMomentType::class, $activityMoment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $activityMoment = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($activityMoment);
            $em->flush();

            return $this->redirectToRoute('activity_moment');
        }
        return $this->render('activity_moment/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}