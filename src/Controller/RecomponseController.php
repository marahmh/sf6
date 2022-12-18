<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;

use App\Entity\AchatRecomponse;
use App\Entity\Recomponse;
use App\Entity\Utilisateur;
use App\Form\RecomponseType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Repository\RecomponseRepository;

/**
 * @Route("/recomponse")
 *
 */
class RecomponseController extends AbstractController
{
    /**
     * @Route("/", name="app_recomponse_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $recomponses = $entityManager
            ->getRepository(Recomponse::class)
            ->findAll();

        return $this->render('recomponse/index.html.twig', [
            'recomponses' => $recomponses,
        ]);
    }

    /**
     * @Route("/new", name="app_recomponse_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recomponse = new Recomponse();
        $form = $this->createForm(RecomponseType::class, $recomponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $recomponse->getPhotoRecomponse();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('upload_directory'),$filename);
            $recomponse->setPhotoRecomponse($filename);
            $entityManager->persist($recomponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recomponse/new.html.twig', [
            'recomponse' => $recomponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/Achat", name="app_recomponse_achat", methods={"GET"})
     */
    public function achat(EntityManagerInterface $entityManager): Response
    {
        $recomponses = $entityManager
            ->getRepository(Recomponse::class)
            ->findAll();

        return $this->render('recomponse/achat.html.twig', [
            'recomponses' => $recomponses,
        ]);
    }



    /**
     * @Route("/{idRecomponse}", name="app_recomponse_show", methods={"GET"})
     */
    public function show(Recomponse $recomponse): Response
    {
        return $this->render('recomponse/show.html.twig', [
            'recomponse' => $recomponse,
        ]);
    }
    /**
     * @Route("/triid", name="triid")
     */

    public function Triid(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT r FROM App\Entity\Recomponse r 
            ORDER BY r.prixRecomponse DESC'
        );


        $rep = $query->getResult();

        return $this->render('recomponse/achat.html.twig', [
            'recomponses' => $rep,
        ]);

    }






    /**
     * @Route("/{idRecomponse}/edit", name="app_recomponse_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Recomponse $recomponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecomponseType::class, $recomponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recomponse/edit.html.twig', [
            'recomponse' => $recomponse,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idRecomponse}", name="app_recomponse_delete", methods={"POST"})
     */
    public function delete(Request $request, Recomponse $recomponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recomponse->getIdRecomponse(), $request->request->get('_token'))) {
            $entityManager->remove($recomponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{idRecomponse}/acheter", name="app_recompense_acheter", methods={"GET", "POST"})
     */
    public function acheter(Request $request, Recomponse $recomponse, EntityManagerInterface $entityManager): Response
    {

        $utilisateur = new Utilisateur();
        $achatrecomp = new AchatRecomponse();
        $idusr = $this->getUser()->getUsername();

        $prix=$recomponse->getPrixRecomponse();

        $solde= intval($entityManager->getRepository(Utilisateur::class)
            ->createQueryBuilder('o')
            ->select('o.soldeUtilisateur')
            ->Where('o.idUtilisateur = :idusr ')
            ->setParameter('idusr', $this->getUser()->getUsername())
            ->getQuery()
            ->getResult());

        $idrecom = $recomponse->getIdRecomponse();
    //echo  "solde " ,(int) $solde;
        if ($prix<=$solde)
        {
            //update solde
            $req=$entityManager->getRepository(Utilisateur::class)
                ->createQueryBuilder('o')
                ->update()
                ->set('o.soldeUtilisateur','o.soldeUtilisateur - :prix')
                ->Where('o.idUtilisateur = :idusr ')
                ->setParameter('idusr', $this->getUser()->getUsername())
                ->setParameter('prix',$prix)
                ->getQuery()
                ->execute();

            //verif exist recomp
            $vrif=$entityManager->getRepository(AchatRecomponse::class)
                ->createQueryBuilder('o')
                ->select('o.quantite')
                ->andWhere('o.idUtilisateur = :idusr  AND o.idRecomponse = :idrecom')
                ->setParameter('idusr', $this->getUser()->getUsername())
                ->setParameter('idrecom', $idrecom)
                ->getQuery()
                ->getOneOrNullResult();
            if ($vrif!=null)
            {
                //add quantite
                $vrif=$entityManager->getRepository(AchatRecomponse::class)
                    ->createQueryBuilder('o')
                    ->update()
                    ->set('o.quantite','o.quantite + 1')
                    ->Where('o.idUtilisateur = :idusr ')
                    ->setParameter('idusr', $this->getUser()->getUsername())
                    ->andWhere('o.idRecomponse = :idrecom')
                    ->setParameter('idrecom', $idrecom)
                    ->getQuery()
                    ->execute();
            }
            else
            {

                //nv-achat
            $user = $entityManager
                ->getRepository(Utilisateur::class)
                ->find($this->getUser()->getUsername());
            $achatrecomp->setIdRecomponse($recomponse);
            $achatrecomp->setIdUtilisateur($user);
            $achatrecomp->setQuantite('1');
            $entityManager->persist($achatrecomp);
            $entityManager->flush();
           }


            $this->addFlash('message', 'Achat effectué avec succès');
        }
        else
        {
            $this->addFlash('message', 'Solde insuffisant');
        }




        return $this->redirectToRoute('app_recomponse_achat', [], Response::HTTP_SEE_OTHER);
    }


    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $recomponses =  $em->getRepository('App:Recomponse')->findEntitiesByString($requestString);

        return $this->render('recomponse/achat.html.twig', [
            'recomponses' => $recomponses,
        ]);


    }
    public function getRealEntities($recomponses){
        foreach ($recomponses as $recomponses){
            $realEntities[$recomponses->getIdRecomponse()] = [$recomponses->getPhotoRecomponse(),$recomponses->getNomRecomponse()];

        }
        return $realEntities;
    }


}
