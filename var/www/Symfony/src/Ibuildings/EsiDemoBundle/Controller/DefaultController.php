<?php

namespace Ibuildings\EsiDemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{

    public function indexAction($name)
    {
        $response = $this->render('IbuildingsEsiDemoBundle:Default:index.html.twig', array('name' => $name));
        $response->setSharedMaxAge(30);
        return $response;
    }

    public function includedAction()
    {
        $response = $this->render('IbuildingsEsiDemoBundle:Default:included.html.twig');
        $response->setSharedMaxAge(10);
        return $response;
    }
}
