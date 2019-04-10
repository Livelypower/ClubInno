<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index()
    {
        $user = $this->getUser();
        if(in_array('ROLE_USER', $user->getRoles()) && !in_array('ROLE_TEACHER', $user->getRoles()) && !in_array('ROLE_ADMIN', $user->getRoles())){
             return $this->render('calendar/index.html.twig', [
                 'userId' => $user->getId()
             ]);
        }else{
            throw new AccessDeniedException();
        }
    }

    /**
     * @Route("admin/calendar", name="admin_calendar")
     */
    public function adminCalendar()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('calendar/admin.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

}
