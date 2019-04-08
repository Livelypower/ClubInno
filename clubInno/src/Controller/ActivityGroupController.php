<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityGroup;
use App\Entity\User;
use App\Form\ActivityGroupForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActivityGroupController extends AbstractController
{
    /**
     * @Route("/admin/activities/groups", name="activity_group")
     */
    public function index()
    {
        $groups = $this->getDoctrine()->getRepository(ActivityGroup::class)->findAll();
        $activities = $this->getDoctrine()->getRepository(Activity::class)->findAll();
        return $this->render('activity_group/index.html.twig', [
            'groups' => $groups,
            'activities' => $activities
        ]);
    }

    /**
     * @Route("/admin/activities/groups/new", name="activity_group_new")
     */
    public function new(Request $request)
    {
        $activityGroup = new ActivityGroup();
        $form = $this->createForm(ActivityGroupForm::class, $activityGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $activityGroup = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($activityGroup);
            $em->flush();

            return $this->redirectToRoute('activity_group');
        }
        return $this->render('activity_group/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/activities/groups/assign/{id}", name="activity_group_assign")
     */
    public function assign(Activity $activity){
        return $this->render('activity_group/assign.html.twig', [
            'activity' => $activity
        ]);
    }
}
