<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/04/2019
 * Time: 9:34
 */

namespace App\Rest;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;


class ActivityMomentController extends AbstractFOSRestController
{
    /**
     * Retrieves ActivityMoment objects that belong to a specified user
     * @Rest\Get("/calendar/{userId}")
     */
    public function getUnassignedUsers(int $userId): View
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $activityGroups = $user->getActivityGroups();

        $activityMoments = array();

        foreach ($activityGroups as $group){
            array_push($activityMoments, $group->getActivityMoments());
        }
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        return View::create($activityMoments, Response::HTTP_OK);
    }
}