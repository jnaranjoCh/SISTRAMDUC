<?php

namespace AppBundle\Security;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TwigExtension extends \Twig_Extension
{
    private $authorizer;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_Function('userHasPermission', array($this, 'userHasPermission')),
        );
    }

    public function userHasPermission($permission)
    {
        if ($this->authorizer == null) {
            $this->authorizer = $this->container->get("app.security.authorizer");
        }
        return $this->authorizer->authorize($permission);
    }
}
