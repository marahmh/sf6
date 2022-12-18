<?php

namespace App\Controller;

use App\Entity\AchatLivre;
use App\Entity\CategorieLivre;
use App\Entity\Livre;
use App\Entity\CommentaireLivre;
use App\Entity\Quiz;
use App\Entity\Utilisateur;
use App\Form\CommentaireLivreType;
use App\Form\LivreType;
use App\Repository\CategorieLivrecRepository;
use App\Repository\LivreRepository;
use App\Repository\CommentaireLivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class ConsulterLivreController extends AbstractController
{
    /**
     * @Route("/consulter/livre", name="app_consulter_livre")
     */
    public function index(EntityManagerInterface $entityManager,Request $request ): Response
    {
        $search=$request->get("search");

        dump($search);
        $filters=$request->get("types");
        dump($filters);

        $types=$entityManager
            ->getRepository(CategorieLivre::class)
            ->findAll();
        $livres = $entityManager
            ->getRepository(Livre::class)
            ->findAll();
        $comments = $entityManager
            ->getRepository(CategorieLivre::class)
            ->findAll();
        if($request->get('ajax'))
        {
            $searchRes= $entityManager
                ->getRepository(Livre::class)
                ->searchLivre($search,$filters);
            dump($searchRes);
            return new JsonResponse([
                'content'=> $this->renderView('consulter_livre/card.html.twig', [
                    'livres' => $searchRes
                ])
            ]);
        }


        return $this->render('consulter_livre/index.html.twig', [
            'livres' => $livres,
            'types'=> $types,
        ]);


    }

    /**
     * @Route("/{idLivre}/consulter/livre/consultation", name="app_consulter_livre-consultation", methods={"GET", "POST"})
     */
    public function consulter(Request  $request ,Livre $livre  , EntityManagerInterface $entityManager): Response
    {
        $confirmationAchat=false;
        $commentaireLivre = new CommentaireLivre();
        $quiz= new Quiz();
        $achat=null;
        if( $this->getUser())
        {
            $achat=$entityManager->getRepository(AchatLivre::class)->findOneBy(array('idLivre' => $request->get("idLivre"), 'idUtilisateur' => $this->getUser()->getUsername()));
            $quiz=$entityManager->getRepository(Quiz::class)->findOneBy(array('idLivre' => $request->get("idLivre")));
        }
        dump("////////////");
        dump($achat);
        if($achat)
        {
            $confirmationAchat=true;

        }
        $form = $this->createForm(CommentaireLivreType::class, $commentaireLivre);
        $form->handleRequest($request);
        $comments = $entityManager
            ->getRepository(CommentaireLivre::class)->findBy(array('idLivre' => $livre->getIdLivre()));
        if ($form->isSubmitted()) {
            if ($commentaireLivre->getContenuCommentaire()==null)
            {
                $this->addFlash('danger', 'Commentaire vide');
                return $this->render('consulter_livre/consultation.html.twig', [
                    'livre' => $livre,
                    'comments' =>$comments,
                    'form'=>$form->createView(),
                    'confirmationAchat'=>$confirmationAchat,
                    'quiz'=>$quiz
                ]);
            }
            $quiz=$entityManager->getRepository(Quiz::class)->findBy(array("idLivre"=>$request->get("idLivre")));
            $livre = $entityManager->getRepository(Livre::class)->find($request->get("idLivre"));
            $utilisateur = $entityManager->getRepository(Utilisateur::class)->findBy(array('idUtilisateur' => $request->get("idUtilisateur")));
            $commentaireLivre->setDateCommentaire(new \DateTime());
            $commentaireLivre->setIdLivre($livre);
            $user=$entityManager->getRepository(Utilisateur::class)->find($this->getUser()->getUsername());
            //$commentaireLivre->setIdUtilisateur($this->getUser()->getUsername());

            $commentaireLivre->setIdUtilisateur($user);
//            dump($commentaireLivre);
            $entityManager->persist($commentaireLivre);
            $entityManager->flush();
        }
        return $this->render('consulter_livre/consultation.html.twig', [
            'livre' => $livre,
            'comments' =>$comments,
            'form'=>$form->createView(),
            'confirmationAchat'=>$confirmationAchat,
            'quiz'=>$quiz
        ]);

    }

    /**
     * @Route("/consulter/livre/pdf", name="app_consulter_livre-pdf", methods={"GET", "POST"})
     */
    public function consulterpdf(EntityManagerInterface $entityManager,Request  $request  ): Response
    {
        $pdf=$request->get("idLivre");
        $src=$entityManager->getRepository(Livre::class)->findBy(array('idLivre' => $pdf));
        dump($src[0]->getContenuLivre());
        $package = new Package(new EmptyVersionStrategy());
        $path = $package->getUrl('uploads/livre/'.$src[0]->getContenuLivre());
        $response = new BinaryFileResponse($path);

        $response->headers->set('Content-Type', 'application/pdf');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_INLINE,
            "pdf-name-at-the-time-of-download.pdf"
        );
        return $response;


    }

    /**
     * @Route("/stat", name="statslivre", methods={"GET", "POST"})
     */
    public function statistique(LivreRepository $liv ,CategorieLivrecRepository $cat)
    {
        $catlivre= $cat->findAll();

        $livrecatNom = array();

        $categoriecount=array();
        foreach ($catlivre as $categorieLivre){
            array_push($livrecatNom,$categorieLivre->getLibelle());
            array_push($categoriecount,$liv->countLivreParCategorie($categorieLivre->getIdCategorieLivre()));
        }
            dump($livrecatNom);
            dump($categoriecount);

        return $this->render('consulter_livre/stat.html.twig',
        [
            'livrecatNom'=> json_encode($livrecatNom),
            'livrecatCategorie'=> json_encode($categoriecount)

        ]
        );
    }

    /**
     * @Route("/ajouterLivre", name="app_livre_ajouter", methods={"GET", "POST"})
     */
    public function newLivre(Request $request, EntityManagerInterface $entityManager): Response
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

            return $this->redirectToRoute('app_consulter_livre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consulter_livre/ajouterLivre.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/achatLivre/{idLivre}", name="app_livre_acheter", methods={"GET", "POST"})
     */
    public function acheter(Request $request, EntityManagerInterface $entityManager): Response
    {
        dump($request->get("idLivre"));
        $livre=$entityManager->getRepository(Livre::class)->find($request->get("idLivre"));
        $user=$entityManager->getRepository(Utilisateur::class)->find($this->getUser()->getUsername());
        if($user->getSoldeUtilisateur()<$livre->getPrixLivre())
        {
            $this->addFlash('danger', 'Solde insuffisant');
            return $this->redirectToRoute('app_consulter_livre-consultation',["idLivre"=>$request->get("idLivre")]);
        }
        $achat= new AchatLivre();
        $achat->setIdUtilisateur($user);
        $achat->setIdLivre($livre);

        $achat->setDateAchat(new \DateTime());
        $user->setSoldeUtilisateur($user->getSoldeUtilisateur()-$livre->getPrixLivre());
        dump($livre);

        $entityManager->persist($user);
        $entityManager->persist($achat);
        $entityManager->flush();

        $commentaireLivre = new CommentaireLivre();
//         return $this->consulter($request,$livre, $entityManager);
        return $this->redirectToRoute('app_consulter_livre-consultation',["idLivre"=>$request->get("idLivre")]);
    }
}
