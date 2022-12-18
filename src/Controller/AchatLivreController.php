<?php

namespace App\Controller;

use App\Entity\AchatLivre;
use App\Form\AchatLivreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/achat/livre")
 */
class AchatLivreController extends AbstractController
{
    /**
     * @Route("/", name="app_achat_livre_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $achatLivres = $entityManager
            ->getRepository(AchatLivre::class)
            ->findAll();

        return $this->render('achat_livre/index.html.twig', [
            'achat_livres' => $achatLivres,
        ]);
    }

    /**
     * @Route("/new", name="app_achat_livre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $achatLivre = new AchatLivre();
        $form = $this->createForm(AchatLivreType::class, $achatLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achatLivre->setDateAchat(new \DateTime());
            $entityManager->persist($achatLivre);
            $entityManager->flush();

            return $this->redirectToRoute('app_achat_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('achat_livre/new.html.twig', [
            'achat_livre' => $achatLivre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_achat_livre_show", methods={"GET"})
     */
    public function show(AchatLivre $achatLivre): Response
    {
        return $this->render('achat_livre/show.html.twig', [
            'achat_livre' => $achatLivre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_achat_livre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AchatLivre $achatLivre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AchatLivreType::class, $achatLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_achat_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('achat_livre/edit.html.twig', [
            'achat_livre' => $achatLivre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_achat_livre_delete", methods={"POST"})
     */
    public function delete(Request $request, AchatLivre $achatLivre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achatLivre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($achatLivre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_achat_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
