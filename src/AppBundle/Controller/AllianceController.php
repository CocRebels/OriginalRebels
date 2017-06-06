<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 4/25/17
 * Time: 1:47 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Alliance;
use AppBundle\Entity\AllianceInvitation;
use AppBundle\Entity\User;
use AppBundle\Form\AllianceCreateType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AllianceController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
            $alliance->setAllianceOwner($user->getId());
            $alliance->setAllianceRules('');
            $user->setAllianceRole('ROLE_OWNER');
            $user->setAlliance($alliance);
            $em = $this->getDoctrine()->getManager();
            $em->persist($alliance);
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Alliance successfully created');

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

    /**
     * @Route("/inviteMember", name="memberInvitation")
     */
    public function inviteMemberAction(Request $request)
    {
        $decoded = '';
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('username', TextType::class)
            ->getForm();

        $form->handleRequest($request);
        $data = $form->getData();
        if (!empty($data))
        {
            $encoders = array( new XmlEncoder(), new JsonEncoder());
            $normalizers = array( new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $finder = $this->get('fos_elastica.finder.app.user');
            $results = $finder->find($data['username']);
            $jsonResult = $serializer->serialize($results, 'json');
            $decoded = json_decode($jsonResult);
        }
        return $this->render(
          'alliance/allianceInviteNewMember.html.twig', array(
              'form' => $form->createView(),
              'data' => $data,
              'json' => $decoded,
            )
        );

    }

    /**
     * @Route("/inviteMember/send/{email}", name="sendInvitation")
     * @param $email
     */
    public function allianceInvitationAction($email)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $invitation = new AllianceInvitation();
        $invitation->setGetter($email);
        $invitation->setSource($user->getEmail());
        $invitation->setMessage('Alliance invitation');
        $invitation->setType('invite');
        $invitation->setSendDate((new \DateTime())->getTimestamp());
        $em->persist($invitation);
        $em->flush();
    }
}