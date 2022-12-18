<?php

namespace App\Controller;

use App\Entity\CategorieLivre;
use App\Form\CategorieLivreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie/livre")
 */
class CategorieLivreController extends AbstractController
{
    /**
     * @Route("/", name="app_categorie_livre_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categorieLivres = $entityManager
            ->getRepository(CategorieLivre::class)
            ->findAll();

        return $this->render('categorie_livre/index.html.twig', [
            'categorie_livres' => $categorieLivres,
        ]);
    }

    /**
     * @Route("/new", name="app_categorie_livre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieLivre = new CategorieLivre();
        $form = $this->createForm(CategorieLivreType::class, $categorieLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieLivre);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_livre/new.html.twig', [
            'categorie_livre' => $categorieLivre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCategorieLivre}", name="app_categorie_livre_show", methods={"GET"})
     */
    public function show(CategorieLivre $categorieLivre): Response
    {
        return $this->render('categorie_livre/show.html.twig', [
            'categorie_livre' => $categorieLivre,
        ]);
    }

    /**
     * @Route("/{idCategorieLivre}/edit", name="app_categorie_livre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategorieLivre $categorieLivre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieLivreType::class, $categorieLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_livre/edit.html.twig', [
            'categorie_livre' => $categorieLivre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCategorieLivre}", name="app_categorie_livre_delete", methods={"POST"})
     */
    public function delete(Request $request, CategorieLivre $categorieLivre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieLivre->getIdCategorieLivre(), $request->request->get('_token'))) {
            $entityManager->remove($categorieLivre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
