<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RootController extends Controller
{
    /**
     * @Route("/", name="inicio")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $user = [
            'short_name' => $user->getNombreCorto(),
            'full_name' => $user->getNombreCompleto()
        ];

        return $this->render('default/index.html.twig', compact('user'));
    }
}
