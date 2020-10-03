<?php


namespace App\Controller;

use App\Entity\Users;
use App\Entity\ValidationAuthorization;
use App\Form\AdminFormAdmStrType;
use App\Form\AdminFormAdmTerrType;
use App\Form\AdminFormOrgaType;
use App\Form\AdminFormSprAdmType;
use App\Form\AuthorizationType;
use App\Form\UsersType;
use App\Repository\EvenementRepository;
use App\Repository\StructureRepository;
use App\Repository\UsersRepository;
use App\Repository\ValidationAuthorizationRepository;
use App\Service\ChangeRole;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users", name="users_")
 * @IsGranted("ROLE_EVENT")
 */
class UsersController extends AbstractController
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
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(
        UsersRepository $usersRepository,
        ObjectManager $objectManager,
        EntityManagerInterface $entityManager,
        StructureRepository $structureRepository
    ) {
        $this->usersRepository = $usersRepository;
        $this->entityManager = $entityManager;
        $this->structureRepository = $structureRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        $users = $this->usersRepository->findAllUser();
        $structure = $this->structureRepository->findAll();

        return $this->render('users/index.html.twig', [
            'users' => $users,
            'structure' => $structure
        ]);
    }

    /**
     * @Route("/validation", name="index_validation")
     * @param ValidationAuthorizationRepository $valid
     * @return Response
     * @IsGranted("ROLE_ORGA")
     */
    public function indexValidation(ValidationAuthorizationRepository $valid)
    {
        $users = $valid->findBy(['isValide'=> false]);
        return $this->render('users/validation.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/show/{id}", name="show", methods={"GET","POST"})
     * @param Request $request
     * @param Users $user
     * @param ValidationAuthorizationRepository $validRepo
     * @param EvenementRepository $event
     * @param UsersRepository $userRepo
     * @param EntityManagerInterface $ema
     * @return Response
     */
    public function show(
        Request $request,
        Users $user,
        ValidationAuthorizationRepository $validRepo,
        EvenementRepository $event,
        UsersRepository $userRepo,
        EntityManagerInterface $ema
    ): Response {
        $allEvent = $event->findAll();
        $authUser = $validRepo->findBy(['user' => $user->getId()]);
        $valid = new ValidationAuthorization();
        $formAutho = $this->createForm(AdminFormOrgaType::class, $valid);
        $formAutho->handleRequest($request);


        if ($formAutho->isSubmitted() && $formAutho->isValid()) {
            $valid->setUser($user);
            $this->entityManager->persist($valid);
            $valid->setIsValide(true);
            $this->entityManager->flush();
            $change = new ChangeRole();
            $change->changeRole($valid, $userRepo, $ema);
            if ($change == true) {
                return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
            }
        }

        $formAdmStr = $this->createForm(AdminFormAdmStrType::class, $valid);
        $formAdmStr->handleRequest($request);

        if ($formAdmStr->isSubmitted() && $formAdmStr->isValid()) {
            $valid->setUser($user);
            $this->entityManager->persist($valid);
            $valid->setIsValide(true);
            $this->entityManager->flush();
            $change = new ChangeRole();
            $change->changeRole($valid, $userRepo, $ema);
            if ($change == true) {
                return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
            }
        }

        $formAdmTerr = $this->createForm(AdminFormAdmTerrType::class, $valid);
        $formAdmTerr->handleRequest($request);

        if ($formAdmTerr->isSubmitted() && $formAdmTerr->isValid()) {
            $user->addArea($formAdmTerr->getData()->getInterventionArea());
            $valid->setUser($user);
            $this->entityManager->persist($valid);
            $valid->setIsValide(true);
            $this->entityManager->flush();

            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        $formSprAdm = $this->createForm(AdminFormSprAdmType::class, $valid);
        $formSprAdm->handleRequest($request);



        if ($formSprAdm->isSubmitted() && $formSprAdm->isValid()) {
            $user->setRoles(['ROLE_ADMIN']);
            $this->entityManager->flush();
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }
            return $this->render('users/show.html.twig', [
                    'auth' => $authUser,
                    'user' => $user,
                    'events' => $allEvent,
                    'formOrga' => $formAutho->createView(),
                    'formAdmStr' => $formAdmStr->createView(),
                    'formAdmTerr'=> $formAdmTerr->createView(),
                    'formSprAdm' => $formSprAdm->createView(),
                ]);
    }


    /**
     * @Route("/new", name="new")
     * @IsGranted("ROLE_ADMIN")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Users();
        $formUsers = $this->createForm(UsersType::class, $user);
        $formUsers->handleRequest($request);

        if ($formUsers->isSubmitted() && $formUsers->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($hash);

            $this->objectManager->persist($user);
            $this->objectManager->flush();
            $this->addFlash('success', 'Compte créée avec succès');
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'formUsers' => $formUsers->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Users $user
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function edit(Request $request, Users $user, UserPasswordEncoderInterface $encoder): Response
    {
        $formUsers = $this->createForm(UsersType::class, $user);
        $formUsers->handleRequest($request);
        if ($formUsers->isSubmitted() && $formUsers->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($hash);
            $this->objectManager->flush();
            $this->addFlash('success', 'Compte modifié avec succés');
            return $this->redirectToRoute('users_show', ['id' => $user->getId()]);
        }

        return $this->render('users/edit.html.twig', [
            'formUsers' => $formUsers->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/demande/{id}", name="authorization", methods={"GET", "POST"})
     * @param Request $request
     * @param Users $user
     * @return Response
     */
    public function authorization(Request $request, Users $user): Response
    {
        $valid = new ValidationAuthorization();
        $formAutho = $this->createForm(AuthorizationType::class, $valid);
        $formAutho->handleRequest($request);

        if ($formAutho->isSubmitted() && $formAutho->isValid()) {
            $valid->setIsValide(false);
            $valid->setUser($user);
            $this->entityManager->persist($valid);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre demande a été prise en compte');
            return $this->redirectToRoute('index_myAccount');
        }
        return $this->render('users/authorization.html.twig', [
            'formAutho' => $formAutho->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param Users $user
     * @return RedirectResponse
     */
    public function delete(Request $request, Users $user)
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Compte supprimé avec succès');
        }

        return $this->redirectToRoute('users_index');
    }

    /**
     * @Route("/accept/{id}", name="accept")
     * @param ValidationAuthorization $valid
     * @param UsersRepository $usersRepo
     * @return RedirectResponse
     */
    public function accept(ValidationAuthorization $valid, UsersRepository $usersRepo, EntityManagerInterface $ema)
    {
        $valid->setIsValide(true);
        $change = new ChangeRole();
        $change->changeRole($valid, $usersRepo, $ema);
        $this->entityManager->flush();
        if ($change == true) {
            if ($valid !== null && $valid->getUser() !== null && $valid->getAuthorization() !== null) {
                $this->addFlash('success', 'l\'utilisateur '
                    . $valid->getUser()->getLastName() . ' '
                    . $valid->getUser()->getLastName() . ' est maintenant '
                    . $valid->getAuthorization()->getName() . '.');
            }
        }
        return $this->redirectToRoute('users_index_validation');
    }

    /**
     * @Route("/refus/{id}", name="refus")
     * @param ValidationAuthorization $valid
     * @return RedirectResponse
     */
    public function refuse(ValidationAuthorization $valid)
    {
        $this->entityManager->remove($valid);
        $this->entityManager->flush();
        $this->addFlash('success', 'Cette demande a bien été refusé');
        return $this->redirectToRoute('users_index_validation');
    }
}
