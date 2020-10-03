<?php


namespace App\Service;

use App\Entity\InterventionArea;
use App\Entity\ValidationAuthorization;
use App\Repository\StructureRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;

class ChangeRole
{
    /**
     * @var UsersRepository
     */
    private $usersRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var StructureRepository
     */
    private $structureRepository;

    public function __construct(
        $usersRepository = UsersRepository::class,
        $entityManager = EntityManagerInterface::class,
        $structureRepository = StructureRepository::class
    ) {
        $this->usersRepository = $usersRepository;
        $this->entityManager = $entityManager;
        $this->structureRepository = $structureRepository;
    }
    public function changeRole(ValidationAuthorization $valid, UsersRepository $usersRepo, EntityManagerInterface $ema)
    {
        if ($valid !== null && $valid->getAuthorization() !== null) {
            $roleAsking = $valid->getAuthorization()->getName();
            switch ($roleAsking) {
                case 'Intervenant':
                    $role = ['ROLE_EVENT'];
                    break;

                case 'admin Structure':
                    $role = ['ROLE_ORGA'];
                    break;

                case 'admin Territoire':
                    $role = ['ROLE_TERRITORY'];
                    break;

                case 'SuperAdmin':
                    $role = ['ROLE_ADMIN'];
                    break;

                default:
                    $role = null;
            }
            if ($valid->getUser() !== null && $valid->getUser()->getId() !== null) {
                $user = $usersRepo->findOneBy(['id' => $valid->getUser()->getId()]);
                if ($user !== null && $valid->getStructure() !== null) {
                    $user->addStaffStructure($valid->getStructure());
                    $user->setRoles($role);
//                    $this->getDoctrine()->getManager()->flush();
                    $ema->flush();
                }
                return true;
            }
        }
    }
}
