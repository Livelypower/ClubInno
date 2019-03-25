<?php

namespace App\Controller;

use App\Form\ApplicationType;
use App\Entity\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;use Symfony\Component\HttpFoundation\Request;


class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index()
    {
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }

    /**
     * @Route("/account/basket", name="account_basket")
     */
    public function basket(Request $request)
    {

        $form = $this->createForm(ApplicationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $request->files->get('application')['motivationLetterPath'];
            $filename = null;
            $uploads_directory = $this->getParameter('uploads_directory');

            $filename = md5(uniqid()) . '.' . $file->guessExtension();

            $file->move(
                $uploads_directory,
                $filename
            );

            $usr= $this->getUser();

            $application = new Application();
            $application->setMotivationLetterPath($filename);

            $application->setUserId($usr->getId());
            $application->setDate(new \DateTime('now'));

            $session = $this->get('session');
            $activities = array();
            foreach($session->get('basket') as $item){
                $activity = $this->getDoctrine()->getRepository(Activity::class)->findOneBy(array('id'=>$item->getId()));
                array_push($activities, $activity);
            }
            $application->setActivities($activities);

            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            return $this->redirectToRoute('account_basket_clear');
        }


        return $this->render('account/basket.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/basket/delete/{id}", requirements={"id": "\d+"}, name="account_basket_delete")
     */
    public function deleteFromBasket(Activity $activity){

        if($this->get('session')->isStarted()){
            $session = $this->get('session');
            $basket = $session->get('basket');
            $index = array_search($activity, $basket);
            array_splice($basket, $index, 1);
            $session->set('basket', $basket);
        }

        return $this->redirectToRoute("account_basket");
    }

    /**
     * @Route("/account/basket/clear", name="account_basket_clear")
     */
    public function clearBasket(){
        if($this->get('session')->isStarted()){
            $session = $this->get('session');
            $session->set('basket', array());
        }

        return $this->redirectToRoute('account_basket');
    }
}
