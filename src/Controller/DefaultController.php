<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        /*return $this->render('index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);*/
        return $this->render('index_med.html.twig', array());
    }

    /**
     * @Route("/admin")
     */
    public function adminAction()
    {
        //return new Response('default/logged_layout:html.twig');
        //return $this->render('layouts/logged_layout.html.twig', array());
        return $this->render('index_med.html.twig', array());

    }

    /**
     * @Route("/myAccount", name="_expediente_my_account")
     */
    public function myAccountAction(){
        return $this->render('default/my_account.html.twig', array());
    }

    /**
     * @Route("", name="")
     */
    public function validateUserPassword($newPassword, $confirmPassword, $translator) {
        if($newPassword != $confirmPassword) return $translator->trans("error.passwords.dont.match");
        if( strlen(trim($newPassword)) == 0 ) return $translator->trans("error.password.emtpy");
        return null;
    }
}
