<?php

namespace App\Controller;

use App\Entity\RecomponseLivre;
use App\Form\RecomponseLivreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/recomponse/livre")
 */
class RecomponseLivreController extends AbstractController
{
    /**
     * @Route("/", name="app_recomponse_livre_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $recomponseLivres = $entityManager
            ->getRepository(RecomponseLivre::class)
            ->findAll();

        return $this->render('recomponse_livre/index.html.twig', [
            'recomponse_livres' => $recomponseLivres,
        ]);
    }

    /**
     * @Route("/new", name="app_recomponse_livre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recomponseLivre = new RecomponseLivre();
        $form = $this->createForm(RecomponseLivreType::class, $recomponseLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recomponseLivre);
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recomponse_livre/new.html.twig', [
            'recomponse_livre' => $recomponseLivre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_recomponse_livre_show", methods={"GET"})
     */
    public function show(RecomponseLivre $recomponseLivre): Response
    {
        return $this->render('recomponse_livre/show.html.twig', [
            'recomponse_livre' => $recomponseLivre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_recomponse_livre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, RecomponseLivre $recomponseLivre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecomponseLivreType::class, $recomponseLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recomponse_livre/edit.html.twig', [
            'recomponse_livre' => $recomponseLivre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_recomponse_livre_delete", methods={"POST"})
     */
    public function delete(Request $request, RecomponseLivre $recomponseLivre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recomponseLivre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recomponseLivre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
    }
}
