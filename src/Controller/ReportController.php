<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Report;
use App\Form\ReportType;
use App\Repository\EvenementRepository;
use App\Repository\ReportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/evenement/{evenement}/report", name="evenement_report_")
 */
class ReportController extends AbstractController
{
    /**
     * @var ReportRepository
     */
    private $reportRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var EvenementRepository
     */
    private $evenementRepository;

    public function __construct(
        ReportRepository $reportRepository,
        EntityManagerInterface $entityManager,
        EvenementRepository $evenementRepository
    ) {
        $this->reportRepository = $reportRepository;
        $this->entityManager = $entityManager;
        $this->evenementRepository = $evenementRepository;
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     * @param Report $report
     * @return Response
     */
    public function show(Report $report): Response
    {
        return $this->render('evenement/report/show.html.twig', [
            'report' => $report,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     * @param Request $request
     * @param Evenement $evenement
     * @return Response
     */
    public function new(Request $request, Evenement $evenement): Response
    {
        $report = new Report();
        $report->setEvenement($evenement);
        $formReport = $this->createForm(ReportType::class, $report);
        $formReport->handleRequest($request);


        if ($formReport->isSubmitted() && $formReport->isValid()) {
            $this->entityManager->persist($report);
            $this->entityManager->flush();
            $this->addFlash('success', 'Bilan créer avec succès');
            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/report/new.html.twig', [
            'report'     => $report,
            'formReport' => $formReport->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Report $report
     * @return Response
     */
    public function edit(Request $request, Report $report): Response
    {
        $formReport = $this->createForm(ReportType::class, $report);
        $formReport->handleRequest($request);

        if ($formReport->isSubmitted() && $formReport->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Bilan modifié avec succés');
            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/report/edit.html.twig', [
            'report'     => $report,
            'formReport' => $formReport->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param Request $request
     * @param Report $report
     * @return RedirectResponse
     */
    public function delete(Request $request, Report $report)
    {
        if ($this->isCsrfTokenValid('delete'.$report->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($report);
            $this->entityManager->flush();
            $this->addFlash('success', 'Bilan supprimé avec succès');
        }
        return $this->redirectToRoute('evenement_index');
    }
}
