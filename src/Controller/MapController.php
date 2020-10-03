<?php

namespace App\Controller;

use App\Entity\Children;
use App\Entity\Evenement;
use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\CategoryRepository;
use App\Repository\ChildrenRepository;
use App\Repository\EvenementRepository;
use App\Repository\StructureRepository;
use App\Repository\ThemeRepository;
use App\Repository\UsersRepository;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query as QueryAlias;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use DateTime;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class MapController
 * @package App\Controller
 * @Route("/", name="index_")
 */
class MapController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @Route("/", name="map", methods={"GET"})
     * @param EvenementRepository $event
     * @param CategoryRepository $category
     * @param ThemeRepository $theme
     * @param StructureRepository $structure
     * @return Response
     */
    public function index(
        EvenementRepository $event,
        CategoryRepository $category,
        ThemeRepository $theme,
        StructureRepository $structure
    ) {
        $date = new DateTime('now');
        $date = $date->format('Y-m-d H:i:s');
        $result = $event->findAllEvents($date);
        $categorieList = $category->findAll();
        $themeList = $theme->findAll();
        $structures = $structure->findAll();

        return $this->render('front/index.html.twig', [
            'controller_name' => 'MapController',
            'eventDetails' => $result,
            'categorieList' => $categorieList,
            'themeList' => $themeList,
            'structures' => $structures,
          ]);
    }

    /**
     * @Route("/ajax/{id}", name="evenement_show_ajax", methods={"GET"})
     * @param Evenement $evenement
     * @return string
     */
    public function showAjax(Evenement $evenement)
    {
        $encoders = [new JsonEncoder()]; // If no need for XmlEncoder
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonMessages = $serializer->serialize($evenement, 'json', [
            'ignored_attributes' => ['structure'],
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
        return new Response($jsonMessages, 200, ['Content-Type' => 'application/json']);

//        return $this->json($evenement, 200);
    }

    /**
     * @Route("/filter", name="filter", methods={"POST", "GET"})
     * @param EvenementRepository $event
     * @param CategoryRepository $category
     * @param ThemeRepository $themes
     * @param StructureRepository $structureRepo
     * @param Request $request
     * @return Response
     */
    public function filter(
        EvenementRepository $event,
        CategoryRepository $category,
        ThemeRepository $themes,
        StructureRepository $structureRepo,
        Request $request
    ) {
        $cat = $request->get('category');
        $theme = $request->get('theme');
        $structure = $request->get('structure');
        $date = $request->get('dateStart');
        $date2 = $request->get('dateStop');
        $options = $request->get('options');
        $dateStart = new DateTime('now');
        $dateStop = new DateTime('now');

        switch ($date) {
            case null:
            case "today":
                break ;
            case "week":
                $dateStop->modify('next sunday');
                break ;
            case "week-end":
                $dateStart->modify('next saturday');
                $dateStop->modify('next sunday');
                break ;
            default:
                $dateStart->modify($date);
                $dateStop->modify($date2);
                break;
        }
        $dateStart = $dateStart->format('Y-m-d H:i:s');
        $dateStop->setTime(23, 59, 59);
        $dateStop = $dateStop->format('Y-m-d H:i:s');
        if ($date === "") {
            $dateStop = null;
        }
        $result = $event->filter($cat, $theme, $structure, $dateStart, $dateStop, $options);
        if ($request->getMethod() === "GET") {
            $result = $event->findEvents($dateStart);
        }
        $categorieList = $category->findAll();
        $themeList = $themes->findAll();
        $structures = $structureRepo->findAll();

        return $this->render('front/index.html.twig', [
            'controller_name' => 'MapController',
            'eventDetails' => $result,
            'categorieList' => $categorieList,
            'themeList' => $themeList,
            'structures' => $structures,
        ]);
    }

    /**
     * @Route("/participate/{event}", name="participate", methods={"POST", "GET"})
     * @param Evenement $event
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse
     */
    public function participate(Evenement $event, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if ($event->getRegisterRequired() === false or $event->getRegisterRequired() === 0) {
            if ($user) {
                if ($user->getParticipation()->contains($event)) {
                    $this->addFlash(
                        'warning',
                        "Vous participez déjà à cet événement (il ne nécessite pas de formulaire)"
                    );
                } else {
                    $user->addParticipation($event);
                    $entityManager->flush();
                    $this->addFlash('warning', "L'inscription à cet événement n'est pas requis");
                }
            } else {
                $this->addFlash('warning', "L'inscription à cet événement n'est pas requis");
            }
        } else {
            if ($user) {
                if ($user->getParticipation()->contains($event)) {
                    $this->addFlash(
                        'warning',
                        "Vous participez déjà à cet événement, vous pouvez le retrouver dans 'Mes Participations'"
                    );
                } else {
                    return $this->redirectToRoute('index_participateForm', ['event' => $event->getId()]);
                }
            } else {
                $this->addFlash('warning', "Vous devez créer un compte ou vous connecter pour vous inscrire");
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->redirectToRoute('index_map');
    }

    /**
     * @Route("/participateForm/{event}", name="participateForm", methods={"GET", "POST"})
     * @param Evenement $event
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UsersRepository $usersRepository
     * @param Mailer $mailer
     * @return Response
     */
    public function participateForm(
        Evenement $event,
        Request $request,
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository,
        Mailer $mailer
    ) {
        $user = $this->getUser();

        if ($request->getMethod() === "POST") {
            $participantsAdult = $request->get('adultNumber');
            $nbrReduct = $request->get('reduct');
            $childrens = $request->get('childNumber');
            $nbrChild = 0;
            $ageChilds = $request->get('childAge');
            if ($childrens) {
                foreach ($childrens as $child) {
                    $nbrChild += $child;
                }
                foreach ($ageChilds as $key => $age) {
                    for ($i = 1; $i <= $childrens[$key]; $i++) {
                        $child = new Children();
                        $child->setAge($age);
                        $child->setEvenement($event);
                        $entityManager->persist($child);
                    }
                }
            } else {
                $nbrChild = 0;
            }
            if (!$nbrReduct) {
                $nbrReduct = 0;
            }
            $totalParticipants = $nbrChild + $nbrReduct + $participantsAdult;
            $lastplaces = $event->getMaxParticipants() - $event->getNbrParticipants();
            if ($totalParticipants <= $lastplaces) {
                $event->setNbrParticipants($totalParticipants);
                $entityManager->flush();
                $user->addParticipation($event);
                $entityManager->flush();
                $localisation = $event->getLocation()." ".$event->getZipCode()." ".$event->getCity();
                $destinataire = $user->getMail();

                $title = $event->getTitle();
                $subject = "Inscription à l'événement $title";
                $variables = ['title'=>$event->getTitle(),
                    'date'=>$event->getDateStart(),
                    'total'=>$totalParticipants,
                    'localisation'=>$localisation];
                $mailer->sendMail($subject, $destinataire, "eventMail", $variables);
                if ($event->getCreator() != null) {
                    $mailInter = $event->getCreator();
                    $intervenant = $usersRepository->findOneBy(['id'=>$mailInter]);
                    if ($intervenant) {
                        $mailer->sendMail($subject, $intervenant->getMail(), "mailInter", $variables);
                    }
                }
                return $this->render("front/validate.html.twig", ['eventDetails'=> $event]);
            } else {
                $this->addFlash("danger", "Nous sommes désolés mais il ne reste que $lastplaces 
                places pour cet événement hors vous souhaitez $totalParticipants places. 
                Veuillez enlever des participants ou annuler votre demande de participation.");
            }
        }

        if ($event->getCost() === 1) {
            return $this->render('front/participationform.html.twig', ['eventDetails'=> $event]);
        } else {
            return $this->render('front/participationformFree.html.twig', ['eventDetails'=> $event]);
        }
    }

    /**
     * @Route("/removeParticipation/{event}", name="removeParticipation", methods={"GET"})
     * @param Evenement $event
     * @param EntityManagerInterface $entityManager
     * @param Mailer $mailer
     * @param UsersRepository $usersRepository
     * @return RedirectResponse
     */
    public function removeParticipation(
        Evenement $event,
        EntityManagerInterface $entityManager,
        Mailer $mailer,
        UsersRepository $usersRepository
    ) {
        if ($event->getCost() === 1) {
            $structure = $event->getStructure();
            if ($structure != null) {
                $structureName = $structure->getCompleteName();
                $structureTel = $structure->getOfficePhone();
                $this->addFlash("danger", "Nous sommes désolés mais vous essayez d'annuler un événement payant,
            veuillez contacter $structureName au 0$structureTel pour voir si cela est possible.");
                return $this->redirectToRoute('index_participations');
            }
        } else {
            $user = $this->getUser();
            $user->removeParticipation($event);
            $entityManager->flush();
            $destinataire = $user->getMail();
            $title = $event->getTitle();
            $subject = "Désinscription de l'événement $title";
            $variablesInter = ['title'=>$event->getTitle(),
                'participant'=> $destinataire,
                'date'=>$event->getDateStart()];
            $variables = ['title'=>$event->getTitle(), 'mailInter'=>'error','phoneInter'=>'error'];

            if ($event->getCreator() != null) {
                $mailInter = $event->getCreator();
                $intervenant = $usersRepository->findOneBy(['id'=>$mailInter]);
                if ($intervenant) {
                    $mailer->sendMail($subject, $intervenant->getMail(), "mailinterDesinscri", $variablesInter);
                    $mailInter = $intervenant->getMail();
                    $phoneInter = $intervenant->getPhone();
                    $variables = ['title'=>$event->getTitle(), 'mailInter'=>$mailInter,'phoneInter'=>$phoneInter];
                }
            }
            $mailer->sendMail($subject, $destinataire, "eventDesinscription", $variables);
            $this->addFlash("success", " L'événement a bien été retiré de vos participations");
            return $this->redirectToRoute('index_map');
        }
    }
  
    /**
     * @Route("/sortienature", name="sortienature", methods={"GET"})
     * @param StructureRepository $structure
     * @return Response
     */
    public function sortieNature(StructureRepository $structure)
    {
        $structures = $structure->findAll();

        return $this->render('front/sortienature.html.twig', ['structures'=>$structures]);
    }

    /**
     * @Route("/myAccount", name="myAccount", methods={"GET", "POST"})
     * @param Request $request
     * @param UsersRepository $userrepo
     * @return Response
     */
    public function edit(Request $request, UsersRepository $userrepo): Response
    {
        $user = $this->getUser();
        $result = $userrepo->findOneBy(['id'=>$user]);
        $formUsers = $this->createForm(RegistrationFormType::class, $result);
        $formUsers->handleRequest($request);
        if ($formUsers->isSubmitted() && $formUsers->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Compte modifié avec succés');
            return $this->redirectToRoute('index_map');
        }

        return $this->render('front/showuser.html.twig', [
            'formUsers' => $formUsers->createView(),
            'user' => $result,
        ]);
    }
     
    /**
     * @Route("/addFavorite/{event}", name="addFavorites", methods={"GET"})
     */
    public function addfavorite(Evenement $event, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();

        if ($user) {
            $user->addFavorite($event);
            $entityManager->flush();
            return $this->render("front/favorites.html.twig");
        } else {
            $this->addFlash('warning', "Vous devez créer un compte ou vous connecter pour ajouter des favoris");
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/removeFavorite/{event}", name="removeFavorites", methods={"GET"})
     * @param Evenement $event
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function removeFavorite(Evenement $event, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $user->removeFavorite($event);
        $entityManager->flush();
        return $this->render("front/favorites.html.twig");
    }

    /**
     * @Route("/favorites", name="favorites", methods={"GET"})
     */
    public function favorites()
    {
        return $this->render("front/favorites.html.twig");
    }

    /**
     * @Route("/participations", name="participations", methods={"GET"})
     */
    public function participations()
    {
        return $this->render("front/participations.html.twig");
    }
}
