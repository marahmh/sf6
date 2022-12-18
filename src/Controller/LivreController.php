<?php

namespace App\Controller;

use DateTimeInterface;
use DateInterval;
use DateTimeZone;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Form\LivreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/livre")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/", name="app_livre_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livres = $entityManager
            ->getRepository(Livre::class)
            ->findAll();

        return $this->render('livre/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    /**
     * @Route("/new", name="app_livre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        dump($livre);
        if ($form->isSubmitted() && $form->isValid()) {
            $image=$form->get('photoLivre')->getData();

            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            $image->move(
                $this->getParameter('upload_directory_livre'),
                $fichier
            );
            $pdf=$form->get('contenuLivre')->getData();
            dump($form->get('contenuLivre')->getData());
            $fichierlivre = md5(uniqid()) . '.' . $pdf->guessExtension();
            $pdf->move(
                $this->getParameter('upload_directory_livre'),
                $fichierlivre
            );

            $livre->setDatePublicationLivre(new \DateTime());
            $user=$entityManager->getRepository(Utilisateur::class)->find($this->getUser()->getUsername());
            $livre->setContenuLivre($fichierlivre);
            $livre->setPhotoLivre($fichier);
            $livre->setIdEcrivainLivre($user);
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('livre/new.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idLivre}", name="app_livre_show", methods={"GET"})
     */
    public function show(Livre $livre): Response
    {
        return $this->render('livre/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    /**
     * @Route("/{idLivre}/edit", name="app_livre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);
        }
        $livre->setIdEcrivainLivre(null);
        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idLivre}", name="app_livre_delete", methods={"POST"})
     */
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getIdLivre(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livre_index', [], Response::HTTP_SEE_OTHER);


    }


    /**
     * @Route("/data/download/", name="livre_data_download")
     */
    public function usersDataDownload(EntityManagerInterface $entityManager, Request  $request)
    {
        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $idLivre=$request->get("idLivre");
        $livre=$entityManager->getRepository(Livre::class)->findBy(array('idLivre' => $idLivre));

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
dump($livre);
        // On génère le html
        $html = $this->renderView('livre/download.html.twig', [
            'livre' => $livre[0],

        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = $livre[0]->getTitreLivre() .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }


}
