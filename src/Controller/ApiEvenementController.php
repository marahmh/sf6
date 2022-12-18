<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\TypeEvenement;
use App\Entity\Utilisateur;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
/**
 * @Route("/api/evenement")
 */
class ApiEvenementController extends AbstractController
{
    /**
     * @Route("/fetch", name="app_api_evenement_fetch")
     */
    public function index(EvenementRepository $repository ,SerializerInterface $serializer): Response
    {
        $evenements=$repository->findAll();
        $json=$serializer->serialize($evenements,'json');


        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    /**
     * @Route("/fetchType", name="app_api_type_fetch")
     */
    public function indexType(EntityManagerInterface $entityManager ,SerializerInterface $serializer): Response
    {
        $types=$entityManager->getRepository(TypeEvenement::class)->findAll();
        $json=$serializer->serialize($types,'json');


        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/add", name="app_api_evenement_add")
     */
    public function ajoutEvenement(Request $request ,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
    { dump("here");
       /* $data=$request->getContent();
        $evenement=$serializer->deserialize($data,Evenement::class,'json');*/
        $evenement=new Evenement();
        $evenement->setAdresseEvenement($request->get("adresse"));
        $evenement->setTitreEvenement($request->get("titre"));
        $evenement->setImage($request->get("image"));
        $evenement->setDateEvenement(new \DateTime());
        $evenement->setDescriptionEvenement($request->get("description"));
        $evenement->setDateCreationEvenement(new \DateTime());
        $evenement->setImage($request->get("image"));

        $user=$entityManager
            ->getRepository(Utilisateur::class)
            ->find($request->get("user"));



        $type=$entityManager
        ->getRepository(TypeEvenement::class)
        ->find($request->get("type"));

        $evenement->setIdCreateur($user);
        $evenement->setTypeEvenement($type);
        $entityManager->persist($evenement);
        $entityManager->flush();
        return new Response("Evenement ajouté avec succes");
    }

    /**
     * @Route("/modifier", name="app_api_evenement_modifier")
     */
    public function modifierEvenement(Request $request ,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
    {
        $data=$request->getContent();
        $evenement=$serializer->deserialize($data,Evenement::class,'json');
        $entityManager->persist($evenement);
        $entityManager->flush();
        return new Response("Evenement modifier avec succes");
    }

    /**
     * @Route("/supprimer/{id}", name="app_api_evenement_supprimer")
     */
    public function supprimerEvenement(Request $request ,SerializerInterface $serializer,EntityManagerInterface $entityManager,int $id): Response
    { dump($id);
      $evenement=$entityManager->getRepository(Evenement::class)->find($id);
      dump($evenement);
        $entityManager->remove($evenement);
        $entityManager->flush();
        return new Response("Evenement supprimer avec succes");
    }
    /**
     * @Route("/fetchEvenement/", name="get_Evenement")
     */
    public function getEvenement(Request $request ,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response{

        $evenement=$entityManager->getRepository(Evenement::class)->find($request->get("id"));
        //$json=$serializer->serialize($evenement,'json');

        $formatted = $serializer->normalize([array("evenement"=>$evenement)]);
        return new JsonResponse($formatted);
    }
}


