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
     * @Route("/password_recovery", name="password_remind")
     */
    public function changePassword(Request $request)
    {
        $form = $this->createForm(SendPasswordChangeType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')
                ->loadUserByUsername($data['email']);
            //TODO: *making a hash for password recovery
            $user->setpassRecoverHash('123456');
            $user->setpassRecoverTimeStamp(new \DateTime());

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', $data['email']);


        }
        return $this->render(
            'security/passwordRemind.html.twig',
            array('form' => $form->createView())
            );
    }
}