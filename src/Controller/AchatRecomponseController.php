<?php

namespace App\Controller;

use App\Entity\AchatRecomponse;
use App\Entity\Recomponse;
use App\Form\AchatRecomponseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/achat/recomponse")
 */
class AchatRecomponseController extends AbstractController
{
    /**
     * @Route("/", name="app_achat_recomponse_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $achatRecomponses = $entityManager
            ->getRepository(AchatRecomponse::class)
            ->findAll();

        return $this->render('achat_recomponse/index.html.twig', [
            'achat_recomponses' => $achatRecomponses,
        ]);
    }

    /**
     * @Route("/new", name="app_achat_recomponse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $achatRecomponse = new AchatRecomponse();
        $form = $this->createForm(AchatRecomponseType::class, $achatRecomponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($achatRecomponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('achat_recomponse/new.html.twig', [
            'achat_recomponse' => $achatRecomponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_achat_recomponse_show", methods={"GET"})
     */
    public function show(AchatRecomponse $achatRecomponse): Response
    {
        return $this->render('achat_recomponse/show.html.twig', [
            'achat_recomponse' => $achatRecomponse,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_achat_recomponse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AchatRecomponse $achatRecomponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AchatRecomponseType::class, $achatRecomponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('achat_recomponse/edit.html.twig', [
            'achat_recomponse' => $achatRecomponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_achat_recomponse_delete", methods={"POST"})
     */
    public function delete(Request $request, AchatRecomponse $achatRecomponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achatRecomponse->getId(), $request->request->get('_token'))) {
            $entityManager->remove($achatRecomponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/recherche_user", name="app_rechercheuser", methods={"GET"})
     */

    public function rechercheuser(EntityManagerInterface $entityManager) : Response
    {

        $recomponses= $entityManager->getRepository(AchatRecomponse::class)
            ->createQueryBuilder('r')
            ->select('r')

            ->where('r.idUtilisateur = :user')
            ->setParameter('user', $this->getUser()->getUsername())
            ->getQuery()
            ->getResult();

        return $this->render('recomponse/livre.html.twig', [
            'recomponses' => $recomponses,
        ]);

    }
}
