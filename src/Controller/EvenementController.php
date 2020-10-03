<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\ImportFile;
use App\Form\EvenementType;
use App\Form\ImportType;
use App\Repository\CategoryRepository;
use App\Repository\EvenementRepository;
use App\Repository\StructureRepository;
use App\Service\Geocoding;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="evenement_index", methods={"GET"})
     * @param EvenementRepository $evenementRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(
        EvenementRepository $evenementRepository,
        CategoryRepository $categoryRepository,
        StructureRepository $strucRepo
    ): Response {
        $user = $this->getUser();
        $roles = $user->getRoles();
        $allStructures = $strucRepo->findAll();
        $strucAreas =[];
        foreach ($allStructures as $structure) {
            $result = $structure->getInterventionArea()->getValues();
            if ($result) {
                array_push($strucAreas, $result);
            }
        }
        $categories = $categoryRepository->findAll();

        //role le plus haut hierarchiquement en premier
        if (in_array('ROLE_ADMIN', $roles)) {
            $result= $evenementRepository->findAll();
//            Pour trouver toutes les areas des structures
            return $this->render('evenement/index.html.twig', [
                'evenements' => $result,
                'categories' => $categories
            ]);
        } elseif (in_array('ROLE_TERRITORY', $roles)) {   // Si role Territory
            $users = $user->getArea()->getValues();
            $allAreasOfUser = [];
            $areaOfUser = [];
            $idStruc = [];
            foreach ($users as $areas) {
                $area = $areas->getInterventionArea();
                array_push($allAreasOfUser, $area);
            }
            foreach ($strucAreas as $result) {
                foreach ($result as $final) {
                    $finalResult = $final->getInterventionArea();
                    if (in_array($finalResult, $allAreasOfUser)) {
                        $areaOfUser = $final->getStructures()->getValues();
                    }
                }
            }
            foreach ($areaOfUser as $id) {
                array_push($idStruc, $id->getId());
            }
            $allEvents = $evenementRepository->findAll();

            return $this->render('evenement/index.html.twig', [
                'evenements' => $allEvents,
                'idStruc' => $idStruc,
                'categories' => $categories
            ]);
        } elseif (in_array('ROLE_ORGA', $roles)) {   // Si role AdminStructure
            $result = $evenementRepository->findAll();
            return $this->render('evenement/index.html.twig', [
                'evenements' => $result,
                'categories' => $categories
            ]);
        } elseif (in_array('ROLE_EVENT', $roles)) {   // Si role Intervenant
            $result = $evenementRepository->findBy(['creator'=>$this->getUser()->getId()]);
            return $this->render('evenement/index.html.twig', [
                'evenements' => $result,
                'categories' => $categories
            ]);
        }
    }

    /**
     * @Route("/new", name="evenement_new", methods={"GET","POST"})
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $city = $request->get("evenement")['city'];
            $street = $request->get("evenement")['location'];
            $zipcode = $request->get("evenement")["zipCode"];
            $service = new Geocoding();
            $geocoding = $service->geocoding($street, $city, $zipcode);

            if (is_string($geocoding)) {
                $adressReformat = json_decode($geocoding, true);
                $lat = $adressReformat[0]['lat'];
                $lon = $adressReformat[0]['lon'];
                $evenement->setLatitude($lat);
                $evenement->setLongitude($lon);
            }
            $evenement->setEventStatus("En attente");
            $evenement->setCreator($this->getUser());
            $entityManager->persist($evenement);
            $entityManager->flush();
            $this->addFlash('success', "Evénement bien créé!");



            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/new/import", name="evenement_new_import", methods={"GET","POST"})
     */
    public function newWithImport(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new ImportFile();
        $form = $this->createForm(ImportType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/newImport.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_show", methods={"GET"})
     * @param Evenement $evenement
     * @return Response
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Evenement $evenement
     * @return Response
     */
    public function edit(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
         ]);
    }

    /**
     * @Route("/{id}", name="evenement_delete", methods={"DELETE"})
     * @param Request $request
     * @param Evenement $evenement
     * @return Response
     */
    public function delete(Request $request, Evenement $evenement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenement_index');
    }

    /**
     * @Route("/participants/{id}", name="evenement_participants", methods={"GET"})
     * @param Evenement $evenement
     * @return Response
     */
    public function participants(Evenement $evenement)
    {
        return $this->render('evenement/participants.html.twig', [
            'evenement' => $evenement,
        ]);
    }
}
