<?php

namespace App\Controller;

use App\Entity\CommentaireLivre;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Form\CommentaireLivreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commentaire/livre")
 */
class CommentaireLivreController extends AbstractController
{
    /**
     * @Route("/", name="app_commentaire_livre_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {


        $commentaireLivres = $entityManager
            ->getRepository(CommentaireLivre::class)
            ->findAll();

        return $this->render('commentaire_livre/index.html.twig', [
            'commentaire_livres' => $commentaireLivres

        ]);
    }

    /**
     * @Route("/new", name="app_commentaire_livre_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaireLivre = new CommentaireLivre();
        $form = $this->createForm(CommentaireLivreType::class, $commentaireLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaireLivre->setDateCommentaire(new \DateTime());
            $entityManager->persist($commentaireLivre);
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire_livre/new.html.twig', [
            'commentaire_livre' => $commentaireLivre,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/newCommentaire", name="app_commentaire_livre_new_consulter", methods={"GET", "POST"})
     */
    public function newCommentaire(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaireLivre = new CommentaireLivre();
        $form = $this->createForm(CommentaireLivreType::class, $commentaireLivre);
        $form->handleRequest($request);
        dump($commentaireLivre);
        if ($form->isSubmitted()) {
            $livre = $entityManager->getRepository(Livre::class)->findBy(array('idLivre' => $request->get("idLivre")));
            $utilisateur = $entityManager->getRepository(Utilisateur::class)->findBy(array('idUtilisateur' => $request->get("idUtilisateur")));
            $commentaireLivre->setDateCommentaire(new \DateTime());
            $commentaireLivre->setIdLivre($livre[0]);
            $commentaireLivre->setIdUtilisateur($utilisateur[0]);
            $commentaireLivre->setContenuCommentaire("aaaaaaaaaa");
dump($commentaireLivre);
            $entityManager->persist($commentaireLivre);
            $entityManager->flush();
        }
        return new Response();
    }

    /**
     * @Route("/{idCommentaire}", name="app_commentaire_livre_show", methods={"GET"})
     */
    public function show(CommentaireLivre $commentaireLivre): Response
    {
        return $this->render('commentaire_livre/show.html.twig', [
            'commentaire_livre' => $commentaireLivre,
        ]);
    }

    /**
     * @Route("/{idCommentaire}/edit", name="app_commentaire_livre_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CommentaireLivre $commentaireLivre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentaireLivreType::class, $commentaireLivre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_commentaire_livre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaire_livre/edit.html.twig', [
            'commentaire_livre' => $commentaireLivre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCommentaire}", name="app_commentaire_livre_delete", methods={"POST"})
     */
    public function delete(Request $request, CommentaireLivre $commentaireLivre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaireLivre->getIdCommentaire(), $request->request->get('_token'))) {
            $entityManager->remove($commentaireLivre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_commentaire_livre_index', [], Response::HTTP_SEE_OTHER);
    }
}
