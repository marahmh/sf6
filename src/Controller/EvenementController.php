<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\ParticipationEvenement;
use App\Entity\TypeEvenement;
use App\Entity\Utilisateur;
use App\Form\EvenementType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="app_evenement_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();
        dump($evenements);
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenements,
        ]);
    }

    /**
     * @Route("/new", name="app_evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                if($form->get('image')->getData()!=null) {
                    $image = $form->get('image')->getData();
                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    $image->move(
                        $this->getParameter('upload_directory'),
                        $fichier

                    );
                    $evenement->setImage($fichier);
                }
            $user=$entityManager
                ->getRepository(Utilisateur::class)
                ->find($this->getUser()->getUsername());
            $evenement->setIdCreateur($user);

            $evenement->setDateCreationEvenement(new \DateTime());
            $entityManager->persist($evenement);
            $entityManager->flush();
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/getEventsForUser", name="evenement-user", methods={"GET", "POST"})
     */
    public function getEvenementForUser(Request $request,EntityManagerInterface $entityManager,SerializerInterface $serializer): Response
    {
        $res= $entityManager
            ->getRepository(Evenement::class)
            ->getEvenementForUser(1);

        $data = $serializer->serialize($res, JsonEncoder::FORMAT);
        return new JsonResponse($data, Response::HTTP_OK, [], true);

    }
    /**
     * @Route("/displayAll", name="app_evenement_cards", methods={"GET"})
     */
    public function showCards(EntityManagerInterface $entityManager,Request $request): Response
    {
        $search=$request->get("search");
        $filters=$request->get("types");
        //dump($filters);
        $evenements = $entityManager
            ->getRepository(Evenement::class)
            ->findAll();
        dump($evenements);

        foreach ($evenements as $evenement) {
                    $nb=$entityManager
                        ->getRepository(ParticipationEvenement::class)->countParticipation($evenement->getIdEvenement());
                    $evenement->setNbParticipant($nb);

                    $estParticipant=$entityManager->getRepository(ParticipationEvenement::class)->estParticipant(1,$evenement->getIdEvenement());

                    if($estParticipant!=0){
                        $evenement->setEstParticipe(1);

                    }else{
                        $evenement->setEstParticipe(0);
                    }

            }
        $e=new Evenement();
        $types=$entityManager
            ->getRepository(TypeEvenement::class)
            ->findAll();
        if($request->get('ajax'))
        {
            $searchRes= $entityManager
            ->getRepository(Evenement::class)
            ->searchEvenement($search,$filters);
           // dump($searchRes);
            return new JsonResponse([
               'content'=> $this->renderView('evenement/contenuCards.html.twig', [
                   'evenements' => $searchRes
               ])
            ]);
        }
        return $this->render('evenement/showCards.html.twig', [
            'evenements' => $evenements,
            'types'=>$types
        ]);
    }

    /**
     * @Route("/{idEvenement}", name="app_evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/{idEvenement}/edit", name="app_evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("delete/{idEvenement}", name="app_evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getIdEvenement(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{idEvenement}/participer", name="app_evenement_participer", methods={"GET", "POST"})
     */
    public function participer(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {

        $particpation = new ParticipationEvenement();

        $user=$entityManager
            ->getRepository(Utilisateur::class)
            ->find(1);
       $verif= $entityManager->getRepository(ParticipationEvenement::class)->estParticipant(1,$evenement);
            if($verif==0) {
                $particpation->setIdUtilisateur($user);
                $particpation->setIdEvenement($evenement);
                $particpation->setDateParticipation(new \DateTime());
                $entityManager->persist($particpation);
                $entityManager->flush();


            }
        return $this->redirectToRoute('app_evenement_cards', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("show/{idEvenement}", name="app_detailEvenement", methods={"GET"})
     */
    public function detailEvenement(Evenement $evenement, EntityManagerInterface $entityManager): Response
    {   $nb=$entityManager
        ->getRepository(ParticipationEvenement::class)->countParticipation($evenement->getIdEvenement());
        $evenement->setNbParticipant($nb);
        return $this->render('evenement/details.html.twig', [
            'evenement' => $evenement,
        ]);
    }

}
