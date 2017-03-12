<?php
/**
 * Created by PhpStorm.
 * User: artur
 * Date: 3/1/17
 * Time: 3:47 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminArea extends Controller
{
    /**
     * @Route("/admin/addChampion", name="addChampion")
     * @Security("is_granted('ROLE_MANAGE_CHAMPIONS')")
     */
    public function addChampionAction()
    {


        return $this->render('admin/addChampion.html.twig');
    }

}