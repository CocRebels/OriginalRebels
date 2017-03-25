<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 2/23/17
 * Time: 3:42 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Security\Hashing;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $website = $this->getParameter('website');
            $user->setRoles(['ROLE_USER']);
            $user->setStatus('N');
            $user->setDateCreated(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $idHash = new Hashing($user->getId());
            // Message after registration to verify your email
            $message = \Swift_Message::newInstance()
                ->setSubject('Hello champion')
                ->setFrom('artur.litvinavicius@gmail.com')
                ->setTo($user->getEmail())
                ->setBody('Here is a link <a href="http://'.$website.'/verify/'.$user->getId().'/'.$idHash->generateHash().'">Verify your email</a>', 'text/html');
            $this->get('mailer')->send($message);
            $this->addFlash('success', 'You sucesfully registered!');

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}