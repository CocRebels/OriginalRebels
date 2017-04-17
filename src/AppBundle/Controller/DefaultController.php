<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Champion;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
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
        $website = 'http://marvel-contestofchampions.wikia.com/';
        $source = $this->getWebsiteSourceCode(
            $website.'wiki/List_of_Champions'
        );

        $test = file_get_contents($source);
        dump($test);exit;
        $crawler = new Crawler($test, 'https');

        $links = $crawler->filter('.mw-content-ltr > ol > li > a')->links();
        $em = $this->getDoctrine()->getManager();
        foreach ($links as $link){
            $test = substr($link->getUri(), 6);
            $trueLink = 'http://marvel-contestofchampions.wikia.com/'.$test;
            $test = file_get_contents($trueLink);
            dump($test);exit;
            $champion = new Champion();
            $champion->setName($link);
            $champion->setSynergy('Synergy');
            $champion->setClass('Class');
            $champion->setAttributes('Attributes');
            $champion->setStars('1');
            $champion->setDescription('Description');
            $champion->setSignature('Signature');
            $champion->setImage('Image');
            $champion->setSkills('Pi');
            $em->persist($champion);
            $em->flush();
        }
        exit;

    }
}
