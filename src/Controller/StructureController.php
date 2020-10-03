<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\CategoryRepository;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class StructureController
 * @package App\Controller
 * @Route("/structure", name="structure_")
 */
class StructureController extends AbstractController
{
    /**
     * @var StructureRepository
     */
    private $structureRepository;
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * StructureController constructor.
     * @param StructureRepository $structureRepository
     * @param CategoryRepository $categoryRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        StructureRepository $structureRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->structureRepository = $structureRepository;
        $this->categoryRepository = $categoryRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index()
    {
        return $this->render('structure/index.html.twig', [
            'structures' => $this->structureRepository->findAll(),
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     * @param Structure $structure
     * @return Response
     */
    public function show(Structure $structure): Response
    {
        return $this->render('structure/show.html.twig', [
            'structure' => $structure
        ]);
    }

    /**
     * @Route("/new", name="new")
     * @IsGranted("ROLE_TERRITORY")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $structure = new Structure();
        $formStructure = $this->createForm(StructureType::class, $structure);
        $formStructure->handleRequest($request);

        if ($formStructure->isSubmitted() && $formStructure->isValid()) {
            $this->entityManager->persist($structure);
            $this->entityManager->flush();
            $this->addFlash('success', 'Créée avec succès');
            return $this->redirectToRoute('structure_index');
        }

        return $this->render('structure/new.html.twig', [
            'structure' =>  $structure,
            'formStructure' => $formStructure->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Structure $structure
     * @return Response
     */
    public function edit(Request $request, Structure $structure): Response
    {
        $formStructure = $this->createForm(StructureType::class, $structure);
        $formStructure->handleRequest($request);
        if ($formStructure->isSubmitted() && $formStructure->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Modifiée avec succès');
            return $this->redirectToRoute('structure_index');
        }

        return $this->render('structure/edit.html.twig', [
            'formStructure' => $formStructure->createView(),
            'structure' => $structure
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     * @IsGranted("ROLE_TERRITORY")
     * @param Request $request
     * @param Structure $structure
     * @return Response
     */
    public function delete(Request $request, Structure $structure): Response
    {
        if ($this->isCsrfTokenValid('delete'. $structure->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($structure);
            $this->entityManager->flush();
            $this->addFlash('success', 'Supprimée avec succès');
        }
        return $this->redirectToRoute('structure_index');
    }
}
