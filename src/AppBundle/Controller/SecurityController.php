<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 2/24/17
 * Time: 6:06 PM
 */

namespace AppBundle\Controller;


use AppBundle\Form\SendPasswordChangeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\DateTime;

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
        $website = $this->getParameter('website');
        $form = $this->createForm(SendPasswordChangeType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')
                ->loadUserByUsername($data['email']);
            //TODO: *making a hash for password recovery
            $urlHash = $this->container
                ->get('security.retrive.data.hashing')
                ->getHash($data['email']);
            $user->setpassRecoverHash($urlHash);
            $user->setpassRecoverTimeStamp(new \DateTime());
            $em->persist($user);
            $em->flush();
            $message = \Swift_Message::newInstance()
                ->setSubject('Hello champion')
                ->setFrom('artur.litvinavicius@gmail.com')
                ->setTo($data['email'])
                ->setBody('Here is a link <a href="http://'.$website.'/recover_password/'.$user->getId().'/'.$urlHash.'">Change your password.</a>', 'text/html');
            $this->get('mailer')->send($message);
            $this->addFlash('success', 'Check your email for email recovery details!');
        }
        return $this->render(
            'security/passwordRemind.html.twig',
            array('form' => $form->createView())
            );
    }

    /**
     * @Route("/recover_password", name="password_recover")
     */
    public function passwordRecoveryAction()
    {

    }

    /**
     * @param $key
     * @param $code
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @param $key
     * @param $code
     * @Route("verify/{key}/{code}", name="verifyEmail")
     */
    public function verifyAction($key, $code)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(['email'=>$key]);
        $status = $user->getStatus();
        if ($status == 'Y' || $user == null) {
            return $this->redirectToRoute('login');
        }
        $urlHash = $this->container
            ->get('security.retrive.data.hashing')
            ->getHash($key);
        if ($code !== $urlHash){
            return $this->redirectToRoute('login');
        }
        $user->setStatus('Y');
        $em->flush();
        $this->addFlash('success', 'Your email is successfully verified! You can now login.');
        return $this->redirectToRoute('login');
    }
}