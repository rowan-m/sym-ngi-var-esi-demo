<?php

namespace Ibuildings\EsiDemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('IbuildingsEsiDemoBundle:Default:index.html.twig', array('name' => $name));
    }
}
