<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 4/15/17
 * Time: 12:38 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\CountriesData;
use AppBundle\Form\EditUsernameType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/user_area", name="user_main_area")
     */
    public function userMainAreaAction(Request $request)
    {
        $user = $this->getUser();
        $userAlliance = $user->getAlliance();
        $em = $this->getDoctrine()->getManager();
        $country = $em
            ->getRepository('AppBundle:CountriesData')
            ->getCountryByKey($user->getCountry());

        return $this->render(
            'user/userMainArea.html.twig', array(
                'user' => $user,
                'user_alliance' => $userAlliance,
                'country' => $country
            )
        );
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/edit_user" , name="edit_user_profile")
     */
    public function editUserAction(Request $request)
    {
        $user = $this->getUser();
        $usernameForm = $this->createForm(EditUsernameType::class);
        $usernameForm->handleRequest($request);


        return $this->render(
            'user/editUser.html.twig'
        );
    }
}