<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CalendarController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar")
     */
    public function index()
    {
        return $this->render('calendar/index.html.twig', [
            'controller_name' => 'CalendarController',
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
