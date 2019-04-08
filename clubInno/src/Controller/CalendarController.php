<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index()
    {
        $user = $this->getUser();
        return $this->render('calendar/index.html.twig', [
            'userId' => $user->getId()
        ]);
    }

    /**
     * @Route("admin/calendar", name="admin_calendar")
     */
    public function adminCalendar()
    {
        return $this->render('calendar/admin.html.twig', [
            'controller_name' => 'CalendarController',
        ]);
    }

}
