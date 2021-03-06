<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 2/24/17
 * Time: 6:06 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\SendPasswordChangeType;
use AppBundle\Security\Hashing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {

    }

    /**
     * @Route("/forgot_password", name="password_remind")
     */
    public function forgotPasswordAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $website = $this->getParameter('website');
        $form = $this->createForm(SendPasswordChangeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')
                ->loadUserByUsername($data['email']);
            if ($user == null){
                $this->addFlash('success', 'Sorry we didnt find a user with that email!');
            }
            else
                {
                    $idHash = new Hashing($data['email']);
                    $user->setpassRecoverHash($idHash->generateHash());
                    $user->setpassRecoverTimeStamp((new \DateTime() )->getTimestamp());
                    $em->persist($user);
                    $em->flush();
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Hello champion')
                        ->setFrom('artur.litvinavicius@gmail.com')
                        ->setTo($data['email'])
                        ->setBody('Here is a link <a href="http://'.$website.'/recover_password/'.$idHash->id.'/'.$idHash->generateHash().'">Change your password.</a>', 'text/html');
                    $this->get('mailer')->send($message);
                    $this->addFlash('success', 'Check your email for email recovery details!');

                }
            }
        return $this->render(
            'security/passwordRemind.html.twig',
            array('form' => $form->createView())
            );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @param $key
     * @param $email
     * @Route("/recover_password/{email}/{key}", name="password_recover")
     */
    public function passwordRecoveryAction($email, $key, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $hash = new Hashing($email);
        $date = (new \DateTime())->getTimeStamp();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->loadUserByUsername($email);
        if ($user == null) {
            return $this->redirectToRoute('login');
        }
        if (strtotime('-1 day', $date) > $user->getPassRecoverTimeStamp() )
        {
            $this->addFlash('success', 'Your password recovery has expired!');
            return $this->redirectToRoute('login');
        }
        if ($key !== $hash->generateHash())
        {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $data['plainPassword']);
            $user->setPassword($password);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'You sucesfully changed your password!');
        }


        return $this->render(
            'security/passwordChange.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @param $key
     * @param $hash
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("verify/{key}/{hash}", name="verifyEmail")
     */
    public function verifyAction($key, $hash)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(['id'=>$key]);
        if ($user == null ){
            return $this->redirectToRoute('login');
        }
        else if ($user->getStatus() == 'Y' || $user->getStatus() == null) {
            return $this->redirectToRoute('login');
        }
        $idHash = new Hashing($user->getId());
        if ($hash !== $idHash->generateHash()){
            return $this->redirectToRoute('login');
        }
        $user->setStatus('Y');
        $user->setRoles(['ROLE_USER']);
        $em->flush();
        $this->addFlash('success', 'Your email is successfully verified! You can now login.');
        return $this->redirectToRoute('login');
    }
}