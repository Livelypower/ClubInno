<?php

namespace App\Controller;

use App\Entity\Semester;
use App\Entity\User;
use App\Form\AccountEditForm;
use App\Form\ApplicationType;
use App\Entity\Application;
use App\Form\ChangePasswordForm;
use App\Form\PasswordForgottenForm;
use App\Form\SemesterFilterType;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function index()
    {
        $activeSemester = $this->getDoctrine()->getRepository(Semester::class)->findOneBy(array('active' => true));
        $user = $this->getUser();
        $activeGroup = null;
        $activeRegistration = null;
        if($activeSemester != null){

            foreach($user->getRegistrations() as $registration){
                if($registration->getSemester() == $activeSemester){
                    $activeRegistration = $registration;
                }
            }

            if($activeRegistration != null){
                foreach($user->getActivityGroups() as $group){
                    if($group->getActivity() == $activeRegistration){
                        $activeGroup = $group;
                    }
                }
            }
        }

        if(in_array('ROLE_ADMIN', $user->getRoles())){
            $role = 'ROLE_ADMIN';
        }elseif(in_array('ROLE_TEACHER', $user->getRoles())){
            $role = 'ROLE_TEACHER';
        }else{
            $role = 'ROLE_USER';
        }

        return $this->render('account/index.html.twig', [
            'activity' => $activeRegistration,
            'group' => $activeGroup,
            'role' => $role
        ]);
    }

    /**
     * @Route("/account/editAccount", name="edit_account")
     */
    public function editAccount(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(AccountEditForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('account/edit_account.html.twig', [
            'accountEditForm' => $form->createView()
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
                    'error' => 'Les mots de passe ne correspondent pas.'
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
            return $this->redirectToRoute('edit_account');
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
        $roles = $this->getUser()->getRoles();

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)){
            $application = new Application();
            $user = $this->getUser();
            $semester = $this->getDoctrine()->getRepository(Semester::class)->findOneBy(array('active'=>1));
            $currentActivities = null;
            $currentActivity = null;
            $error = '';

            foreach ($user->getRegistrations() as $registration){
                if($registration->getSemester()->getId() == $semester->getId()){
                    $currentActivity = $registration;
                }
            }

            foreach ($user->getApplications() as $application){
                if($application->getSemester()->getId() == $semester->getId()){
                    if(!empty($application->getActivities())){
                        $currentActivities = $application->getActivities();
                    }
                }
            }

            $form = $this->createForm(ApplicationType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $session = $this->get('session');
                if($session->has("basket")){
                    if(count($session->get('basket')) != 0 || count($currentActivities) != 0){
                        $file = $form->get('motivationLetterPath')->getData();
                        $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

                        try {
                            $file->move(
                                $this->getParameter('uploads_directory'),
                                $fileName
                            );
                        } catch (FileException $e) {
                            // ... handle exception if something happens during file upload
                        }

                        $semester = $this->getDoctrine()->getRepository(Semester::class)->findOneBy(array('active' => 1));

                        $usr= $this->getUser();
                        $application->setMotivationLetterPath($fileName);
                        $application->setUser($usr);
                        $application->setDate(new \DateTime('now'));
                        $application->setSemester($semester);


                        $activities = array();
                        if($session->has('basket')){
                            foreach($session->get('basket') as $item){
                                $activity = $this->getDoctrine()->getRepository(Activity::class)->findOneBy(array('id'=>$item->getId()));
                                array_push($activities, $activity);
                            }
                            if($currentActivities != null){
                                foreach($currentActivities as $item){
                                    $activity = $this->getDoctrine()->getRepository(Activity::class)->findOneBy(array('id'=>$item->getId()));
                                    if(!in_array($activity, $activities)){
                                        array_push($activities, $activity);
                                    }
                                }
                            }

                        }
                        $application->setActivities($activities);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($application);
                        $em->flush();
                    }
                    else{
                        $error = "Choisir une activité.";
                        return $this->render('account/basket.html.twig', [
                            'form' => $form->createView(),
                            'error' => $error,
                            'currentActivity' => $currentActivity,
                            'currentActivities' => $currentActivities
                        ]);
                    }
                }
                return $this->redirectToRoute('account_basket_clear');
            }

            return $this->render('account/basket.html.twig', [
                'form' => $form->createView(),
                'error' => $error,
                'currentActivity' => $currentActivity,
                'currentActivities' => $currentActivities
            ]);
        }
        else{
            throw new AccessDeniedException();
        }
    }

    /**
     * @Route("/account/basket/delete/{id}", requirements={"id": "\d+"}, name="account_basket_delete")
     */
    public function deleteFromBasket(Activity $activity){
        $roles = $this->getUser()->getRoles();

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)) {
            if ($this->get('session')->isStarted()) {
                $session = $this->get('session');
                $basket = $session->get('basket');
                $index = array_search($activity, $basket);
                array_splice($basket, $index, 1);
                $session->set('basket', $basket);
            }

            return $this->redirectToRoute("account_basket");
        }else{
            throw new AccessDeniedException();
        }
    }

    /**
     * @Route("/account/basket/clear", name="account_basket_clear")
     */
    public function clearBasket(){
        $roles = $this->getUser()->getRoles();

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)) {
            if ($this->get('session')->isStarted()) {
                $session = $this->get('session');
                $session->set('basket', array());
            }

            return $this->redirectToRoute('account_basket');
        }else{
            throw new AccessDeniedException();
        }
    }

    /**
     * @Route("/account/basket/delete/cand/{id}", requirements={"id": "\d+"}, name="account_basket_delete_cand")
     */
    public function deleteFromCandidature(Activity $activity){
        $user = $this->getUser();
        $roles = $user->getRoles();
        $semester = $this->getDoctrine()->getRepository(Semester::class)->findOneBy(array('active'=>1));

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)) {
            $currentActivities = null;
            foreach ($user->getApplications() as $application){
                if($application->getSemester()->getId() == $semester->getId()){
                    $currentApplication = $application;
                    if(!empty($application->getActivities())){
                        $currentActivities = $application->getActivities();
                    }
                }
            }

            $ctr = 0;

            foreach($currentActivities as $currentActivity){
                if($activity->getId() == $currentActivity->getId()){
                    unset($currentActivities[$ctr]);
                }
                $ctr++;
            }

            $application = $this->getDoctrine()->getRepository(Application::class)->find($currentApplication->getId());


            if(count($currentActivities) == 0){
                $em = $this->getDoctrine()->getManager();
                $em->remove($application);
                $em->flush();
            }else{
                $application->setActivities($currentActivities);
                $em = $this->getDoctrine()->getManager();
                $em->persist($application);
                $em->flush();
            }

            return $this->redirectToRoute("account_basket");
        }else{
            throw new AccessDeniedException();
        }
    }

    /**
     * @Route("/account/basket/clear/cand", name="account_basket_clear_cand")
     */
    public function clearCandidature(){
        $user = $this->getUser();
        $roles = $user->getRoles();
        $semester = $this->getDoctrine()->getRepository(Semester::class)->findOneBy(array('active'=>1));

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)) {

            foreach ($user->getApplications() as $application){
                if($application->getSemester()->getId() == $semester->getId()){
                    $currentApplication = $application;
                }
            }

            $application = $this->getDoctrine()->getRepository(Application::class)->find($currentApplication->getId());
            $em = $this->getDoctrine()->getManager();
            $em->remove($application);
            $em->flush();

            return $this->redirectToRoute('account_basket');
        }else{
            throw new AccessDeniedException();
        }
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
    public function viewApplications(Request $request){
        $roles = $this->getUser()->getRoles();

        if(in_array('ROLE_USER', $roles) && !in_array('ROLE_ADMIN', $roles) && !in_array('ROLE_TEACHER', $roles)) {
            $user = $this->getUser();
            $applications = $this->getDoctrine()->getRepository(Application::class)->findBy(['user' => $user], ['date' => 'DESC']);
            $semesters = $this->getDoctrine()->getRepository(Semester::class)->findAll();

            $form = $this->createForm(SemesterFilterType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $semestersForm = $form->get('semesters')->getData();

                if($semestersForm != null || !empty($semestersForm)){
                    $semesters = array();
                    foreach ($semestersForm as $semesterForm){
                        $semester = $this->getDoctrine()->getRepository(Semester::class)->find($semesterForm->getId());
                        array_push($semesters, $semester);
                    }
                }
            }

            return $this->render('account/viewApplications.html.twig', [
                'applications' => $applications,
                'semesters' => $semesters,
                'form' => $form->createView()
            ]);
        }else{
            throw new AccessDeniedException();
        }
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

    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}
