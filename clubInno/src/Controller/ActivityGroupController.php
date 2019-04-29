<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\ActivityGroup;
use App\Form\ActivityGroupForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
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
     * @Route("/admin/activities/groups/edit/{id}", name="activity_group_edit")
     */
    public function editActivityGroup(Request $request, ActivityGroup $activityGroup)
    {
        $form = $this->createForm(ActivityGroupForm::class, $activityGroup);
        $group = $this->getDoctrine()->getRepository(ActivityGroup::class)->find($activityGroup->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $activityGroup = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($activityGroup);
            $em->flush();

            return $this->redirectToRoute('activity_group');
        }
        return $this->render('activity_group/edit.html.twig', [
            'form' => $form->createView(),
            'group' => $group
        ]);
    }

    /**
     * @Route("/admin/activities/groups/delete/{id}", name="activity_group_delete")
     */
    public function deleteActivityGroup(ActivityGroup $activityGroup){
        $em = $this->getDoctrine()->getManager();
        $em->remove($activityGroup);
        $em->flush();

        return $this->redirectToRoute('activity_group');
    }

    /**
     * @Route("/admin/activities/groups/assign/{id}", name="activity_group_assign")
     */
    public function assign(Activity $activity){
        $user = $this->getUser();

        if ($user == null){
            $apiToken = null;
        } else {
            $apiToken = $user->getApiToken();
        }

        return $this->render('activity_group/assign.html.twig', [
            'activity' => $activity,
            'apiToken' => $apiToken
        ]);
    }
}
