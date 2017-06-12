<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Permiso;

class Authorizer
{
    private $tokenStorage;
    private $manager;
    private $userPermisosMap;

    public function __construct(TokenStorageInterface $tokenStorage, ObjectManager $manager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->manager = $manager;
    }

    public function authorize(string $permission)
    {
        if (is_null($this->userPermisosMap)) {
            $this->userPermisosMap = $this->getUserPermisosMap();
        }

        return isset($this->userPermisosMap[$permission]);
    }

    public function getUserPermisosMap()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $repository = $this->manager->getRepository(Permiso::class);

        $permisoNombres = $repository->findPermisoNombresByUserCedula($user->getCedula());

        $userPermisosMap = [];
        foreach ($permisoNombres as $permiso) {
            $userPermisosMap[$permiso['nombre']] = true;
        }

        return $userPermisosMap;
    }
}
