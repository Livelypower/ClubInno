<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/04/2019
 * Time: 10:04
 */

namespace App\Rest;


use App\Entity\Activity;
use App\Entity\ActivityGroup;
use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class ActivityController extends AbstractFOSRestController
{
    /**
     * Retrieves user objects registered to a specific activity but not assigned to any groups of that activity
     * @Rest\Get("/activity/groups/{activityId}")
     */
    public function getUnassignedUsers(int $activityId): View
    {
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($activityId);
        $groups = $this->getDoctrine()->getRepository(ActivityGroup::class)->findBy(['activity' => $activity]);

        $users = $activity->getUsers();
        $unassignedUsers = [];
        $assignedUsers = [];


        //Iterate over every group belonging to the activity
        foreach ($users as $user) {
            $inAGroup = false;

            foreach ($groups as $group) {
                foreach ($group->getUsers() as $groupUser) {
                    if($user->getId() == $groupUser->getId()){
                        $inAGroup = true;
                    }
                }
            }

            if(!$inAGroup){
                array_push($unassignedUsers, $user);
            }
        }


        $response = ["groups" => $groups, "unassignedUsers" => $unassignedUsers];
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($response, Response::HTTP_OK);
    }

    /**
     * Creates an Comment resource
     * @Rest\Post("/activity/groups/addusers/{groupId}")
     * @param Request $request
     * @return View
     */
    public function addUsers(Request $request, int $groupId): View
    {
        $userIds = [];
        $users = [];
        $group = $this->getDoctrine()->getRepository(ActivityGroup::class)->find($groupId);

        if(!empty($request->get('users'))){
            foreach ($request->get('users') as $userId) {
                array_push($userIds, $userId);
            }

            foreach ($userIds as $userId) {
                array_push($users, $this->getDoctrine()->getRepository(User::class)->find($userId));
            }

            $groupUsers = [];
            foreach ($group->getUsers() as $user){
                array_push($groupUsers, $user);
            }

            foreach ($users as $user) {
                if (!in_array($user, $groupUsers)) {
                    array_push($groupUsers, $user);
                }
            }

            $group->setUsers($groupUsers);
        }else{
            $group->setUsers($users);
        }


        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();

        // In case our POST was a success we need to return a 201 HTTP CREATED response
        return View::create($group, Response::HTTP_CREATED);

    }
}