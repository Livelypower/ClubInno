<?php
/**
 * Created by PhpStorm.
 * User: korneel
 * Date: 3/25/2019
 * Time: 11:35 AM
 */

namespace App\Rest;

use App\Entity\ActivityGroup;
use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Activity;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;

class RegistrationController  extends AbstractFOSRestController
{
    /**
     * Creates a Registration resource
     * @Rest\Post("/admin/registration/add")
     * @param Request $request
     * @return View
     */
    public function postRegistration(Request $request): View
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('userId'));
        $assignedActs = $request->get('activities');
        $activities = array();
        $groups = array();
        $em = $this->getDoctrine()->getManager();

        if(!empty($assignedActs)){
            foreach ($assignedActs as $acts){
                $activty = $this->getDoctrine()->getRepository(Activity::class)->find($acts);
                array_push($activities, $activty);
            }
        }

        foreach($user->getActivityGroups() as $userGroup){
            foreach($activities as $activity){
                if($activity->getId() == $userGroup->getActivity()->getId()){
                    $group = $this->getDoctrine()->getRepository(ActivityGroup::class)->find($userGroup->getId());
                    array_push($groups, $group);
                    $users = array();
                    foreach($userGroup->getUsers() as $groupUser){
                        if($groupUser->getId() != $user->getId()){
                            array_push($users, $groupUser);
                        }
                    }
                    $userGroup->setUsers($users);
                    $em->persist($userGroup);
                    $em->flush();
                }
            }
        }

        $user->setRegistrations($activities);
        $user->setActivityGroups($groups);

        $em->persist($user);
        $em->flush();

        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($user, Response::HTTP_CREATED);
    }
}