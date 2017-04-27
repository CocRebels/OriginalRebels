<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Champion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function showAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $champions = $em->getRepository('AppBundle:Champion')
            ->findAll();

        return $this->render('default/index.html.twig', array(
            'champions' => $champions
        ));
    }

    /**
     * @Route("/testing", name="testing")
     */
    public function domAction()
    {
        $finder = $this->get('fos_elastica.finder.app.user');
        $results = $finder->find('');
        return new JsonResponse($results);
    }
}
