<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 4/25/17
 * Time: 1:47 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Alliance;
use AppBundle\Form\AllianceCreate;
use AppBundle\Form\AllianceCreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AllianceController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/create_alliance", name="alliance_creation")
     */
    public function createAllianceAction(Request $request)
    {
        $alliance = new Alliance();
        $form = $this->createForm(AllianceCreateType::class, $alliance);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $this->getUser();
            $alliance->setUserNumber(1);
            $alliance->setAllianceOwner($user->getUsername());
            $user->setAllianceRole('ROLE_OWNER');
            $user->setAlliance($alliance);
            $em = $this->getDoctrine()->getManager();
            $em->persist($alliance);
            $em->persist($user);
            $em->flush();
            $this->addFlash('succes', 'Alliance successfully created');

            return $this->redirectToRoute('homepage');
        }

        return $this->render(
            'alliance/createAlliance.html.twig',
            array('form' => $form->createView())
        );

    }

    /**
     * @Route("/alliance", name="alliance_main")
     */
    public function allianceMainAction()
    {
        if ($this->getUser()->getAlliance() === null){
            return $this->redirectToRoute('alliance_creation');
        }

        $allianceId = $this->getUser()->getAlliance();

        $em = $this->getDoctrine()->getManager();
        $alliance = $em->getRepository('AppBundle:Alliance')
            ->find($allianceId);

        $members = $em->getRepository('AppBundle:User')
            ->findAllAllianceMembers($alliance);

        return $this->render(
            'alliance/allianceMain.html.twig', array(
                'alliance' => $alliance,
                'allianceMembers' => $members,
            )
        );

    }
}