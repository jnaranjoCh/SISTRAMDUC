<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SecurityController extends Controller
{

    /**
     * @Route("/login", name="security_login_form")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $last_username = $authenticationUtils->getLastUsername();

        return $this->render( 'security/login.html.twig', compact('last_username', 'error') );
    }

    /**
     * This is the route the login form submits to.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the login automatically. See form_login in app/config/security.yml
     *
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
