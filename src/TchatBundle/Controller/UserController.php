<?php

namespace TchatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{

    public function whoIsOnlineAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository('TchatBundle:User')->getActive();

        return $this->render('TchatBundle:User:whoIsOnline.html.twig', array('users' => $users));
    }
}
