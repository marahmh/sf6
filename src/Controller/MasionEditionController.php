<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\MasionEdition;
use App\Form\MasionEditionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/masion/edition")
 */
class MasionEditionController extends AbstractController
{
    /**
     * @Route("/", name="app_masion_edition_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $masionEditions = $entityManager
            ->getRepository(MasionEdition::class)
            ->findAll();


        return $this->render('masion_edition/index.html.twig', [
            'masion_editions' => $masionEditions,
        ]);
    }


    /**
     * @Route("/listpdf", name="maison_list", methods={"GET"})
     */
    public function listp(EntityManagerInterface $entityManager): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $masionEditions = $entityManager
            ->getRepository(MasionEdition::class)
            ->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('masion_edition/pdf.html.twig', [
            'masion_editions' => $masionEditions,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

    }
    /**
     * @Route("/new", name="app_masion_edition_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $masionEdition = new MasionEdition();
        $form = $this->createForm(MasionEditionType::class, $masionEdition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($masionEdition);
            $entityManager->flush();

            return $this->redirectToRoute('app_masion_edition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('masion_edition/new.html.twig', [
            'masion_edition' => $masionEdition,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/displayAll", name="app_maison_cards")
     */
    public function showCards(EntityManagerInterface $entityManager): Response
    {
        $maison = $entityManager
            ->getRepository(MasionEdition::class)
            ->findAll();

        return $this->render('masion_edition/showMaison.html.twig', [
            'maison' => $maison,
        ]);
    }

    /**
     * @Route("/{idMaisonEdition}", name="app_masion_edition_show", methods={"GET"})
     */
    public function show(MasionEdition $masionEdition): Response
    {
        return $this->render('masion_edition/show.html.twig', [
            'masion_edition' => $masionEdition,
        ]);
    }
    /**
     * @Route("/{idMaisonEdition}/maison", name="app_masion_edition_show2", methods={"GET"})
     */
    public function show2(MasionEdition $masionEdition): Response
    {
        return $this->render('masion_edition/showMaisonId.html.twig', [
            'masion_edition' => $masionEdition,
        ]);
    }
    /**
     * @Route("/{idMaisonEdition}/maison/map", name="app_masion_edition_map", methods={"GET"})
     */
    public function showmap(MasionEdition $masionEdition): Response
    {
        return $this->render('masion_edition/mapMaison.html.twig', [
            'masion_edition' => $masionEdition,
        ]);
    }
    /**
     * @Route("/{idMaisonEdition}/edit", name="app_masion_edition_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, MasionEdition $masionEdition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MasionEditionType::class, $masionEdition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_masion_edition_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('masion_edition/edit.html.twig', [
            'masion_edition' => $masionEdition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idMaisonEdition}", name="app_masion_edition_delete", methods={"POST"})
     */
    public function delete(Request $request, MasionEdition $masionEdition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$masionEdition->getIdMaisonEdition(), $request->request->get('_token'))) {
            $entityManager->remove($masionEdition);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_masion_edition_index', [], Response::HTTP_SEE_OTHER);
    }




}
