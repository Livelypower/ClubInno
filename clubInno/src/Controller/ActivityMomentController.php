<?php

namespace App\Controller;

use App\Entity\ActivityMoment;
use App\Form\ActivityMomentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class ActivityMomentController extends AbstractController
{
    /**
     * @Route("/activity/moment", name="activity_moment")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $activityMoments = $paginator->paginate(
            $this->getDoctrine()->getRepository(ActivityMoment::class)->findBy([],['startDate' => 'DESC']),
            $request->query->getInt('page',1),
            20
        );

        return $this->render('activity_moment/index.html.twig', [
            'activityMoments' => $activityMoments,
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

    /**
     * @Route("/activity/moment/edit/{id}", name="activity_moment_edit")
     */
    public function editActivityMoment(Request $request, ActivityMoment $activityMoment){
        $form = $this->createForm(ActivityMomentType::class, $activityMoment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $activityMoment = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($activityMoment);
            $em->flush();

            return $this->redirectToRoute('activity_moment');
        }
        return $this->render('activity_moment/edit.html.twig', [
            'form' => $form->createView(),
            'moment' => $activityMoment
        ]);
    }

    /**
     * @Route("/activity/moment/delete/{id}", name="activity_moment_delete")
     */
    public function deleteActivityMoment(ActivityMoment $activityMoment){
        $em = $this->getDoctrine()->getManager();
        $em->remove($activityMoment);
        $em->flush();

        return $this->redirectToRoute("activity_moment");
    }


}
