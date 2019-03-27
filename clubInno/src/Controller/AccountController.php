<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountEditForm;
use App\Form\ApplicationType;
use App\Entity\Application;
use App\Form\ChangePasswordForm;
use App\Form\PasswordForgottenForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountEditForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('account/index.html.twig', [
            'accountEditForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/changePassword", name="changePassword")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            $new_pwd = $form->get('new_password')->getData();
            $new_pwd_confirm = $form->get('new_password_confirm')->getData();

            $checkPass = $passwordEncoder->isPasswordValid($user, $old_pwd);
            if ($checkPass === true && $new_pwd === $new_pwd_confirm){
               $user->setPassword($passwordEncoder->encodePassword($user, $new_pwd_confirm));
            } elseif ($checkPass === false && $new_pwd === $new_pwd_confirm){
                return $this->render('account/changePassword.html.twig', [
                    'changePasswordForm' => $form->createView(),
                    'error' => 'Your current password is incorrect.'
                ]);
            } elseif ($checkPass === true && $new_pwd !== $new_pwd_confirm){
                return $this->render('account/changePassword.html.twig', [
                    'changePasswordForm' => $form->createView(),
                    'error' => 'Both passwords don\'t match'
                ]);
            } else {
                return $this->render('account/changePassword.html.twig', [
                    'changePasswordForm' => $form->createView(),
                    'error' => 'Your current password is incorrect.'
                ]);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('account/changePassword.html.twig', [
            'changePasswordForm' => $form->createView(),
            'error' => null
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

            $application->setUser($usr);
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

    /**
     * @Route("/account/forgotPassword", name="forgot_password")
     */
    public function forgotPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer){
        $user = new User();
        $form = $this->createForm(PasswordForgottenForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $form->get('email')->getData()]);
            if ($user !== null){
                $new_pass = $this->random_str(32);
                $user->setPassword($passwordEncoder->encodePassword($user, $new_pass));
                $entityManager->persist($user);

                $entityManager->flush();

                $message = (new \Swift_Message('Password Changed'))
                    ->setFrom('kasumiiwamoto69@gmail.com')
                    ->setTo($form->get('email')->getData())
                    ->setBody(
                        $this->renderView('emails/passwordChanged.html.twig', [
                            'password' => $new_pass,
                            'user' => $user
                        ])
                    );
                $mailer->send($message);
                return $this->render('account/mailSent.html.twig', [
                    'new_pass' => $new_pass,
                ]);
            } else {
                return $this->render('account/passwordForgotten.html.twig', [
                    'passwordForgottenForm' => $form->createView(),
                    'error' => 'No user with that email address was found.'
                ]);
            }

        }
        return $this->render('account/passwordForgotten.html.twig', [
            'passwordForgottenForm' => $form->createView(),
            'error' => null
        ]);
    }

    /**
     * @Route("/account/applications", name="account_applications")
     */
    public function viewApplications(){
        $user = $this->getUser();

        $applications = $this->getDoctrine()->getRepository(Application::class)->findBy(['user' => $user], ['date' => 'DESC']);

        return $this->render('account/viewApplications.html.twig', [
            'applications' => $applications
        ]);
    }

    private function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
