<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Authorizer
{
    private $tokenStorage;
    private $manager;
    private $user;

    public function __construct(TokenStorageInterface $tokenStorage, ObjectManager $manager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
    }

    public function authorize(string $permission)
    {
        if (is_null($this->user)) {
            $this->setUser();
        }

        foreach ($this->user->getRolesAsObjects() as $rol) {
            foreach ($rol->getPermisos() as $permiso) {
                if ($permiso->getNombre() == $permission) {
                    return true;
                }
            }
        }

        return false;
    }

    public function setUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $repository = $this->manager->getRepository(get_class($user));

        $this->user = $repository->findOneByCedula($user->getCedula());
    }
}
