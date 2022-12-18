<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Livre;
use App\Entity\CategorieLivre;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
/**
 * @Route("/api/livre")
 */
class ApiLivreController extends AbstractController
{
    /**
     * @Route("/fetch", name="app_api_livre")
     */
    public function index(LivreRepository  $repository,SerializerInterface $serializer): Response
    {
        $livre=$repository->findAll();
        $json=$serializer->serialize($livre,'json');


        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/add", name="app_api_livre_add")
     */
    public function ajoutLivre(Request $request ,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
    { dump("here");
        /* $data=$request->getContent();
         $evenement=$serializer->deserialize($data,Evenement::class,'json');*/
        $livre=new Livre();
         dump($request);

            $livre->setTitreLivre($request->get("Titre"));
        $livre->setDescriptionLivre($request->get("Description"));
        $livre->setPrixLivre((int)$request->get("prix"));
        //$livre->setPrixLivre(500);
        $livre->setDatePublicationLivre(new \DateTime());
        $livre->setPhotoLivre("test.png");
        $livre->setContenuLivre("test.png");


        $user=$entityManager
            ->getRepository(Utilisateur::class)
            ->find(6);
        $type=$entityManager
            ->getRepository(CategorieLivre::class)
            ->find(1);

        $livre->setIdEcrivainLivre($user);
        $livre->setIdCategorieLivre($type);
        dump($livre);
        $entityManager->persist($livre);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize([array("response"=>"test")]);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/modifier", name="app_api_livre_modifier")
     */
    public function modifierLivre(Request $request ,SerializerInterface $serializer,EntityManagerInterface $entityManager): Response
    {
        $data=$request->getContent();
        $livre=$serializer->deserialize($data,Livre::class,'json');
        $entityManager->persist($livre);
        $entityManager->flush();
        return new Response("livre modifier avec succes");
    }

    /**
     * @Route("/supprimer/{id}", name="app_api_livre_supprimer")
     */
    public function supprimerLivre(Request $request ,SerializerInterface $serializer,EntityManagerInterface $entityManager,int $id): Response
    { dump($id);
        $livre=$entityManager->getRepository(Livre::class)->find($id);
        dump($livre);
        $entityManager->remove($livre);
        $entityManager->flush();
        return new Response("Livre supprimer avec succes");
    }

    /**
     * @Route("/show/{id}", name="showLivre")
     */
    public function showjson(EntityManagerInterface $entityManager, NormalizerInterface $Normalizer, Request $request , $id ): Response
    {
        $jsonContent=array();
        $livre = $entityManager
            ->getRepository(Livre::class)
            ->findAll();
        $output=[];
        foreach ($livre as $plc){
            if($plc->getIdLivre()==$id){

                $jsonContent1 = $Normalizer->normalize($plc) ;
                array_push($jsonContent,$jsonContent1);}
        }
        /* $pics=$picturesRepository->findBy(["place"=>$place->getId()]);
         $jsonContent = $Normalizer->normalize($place,'json',['groups'=>'post:read']) ;
         $jsonContent1 = $Normalizer->normalize($pics[0],'json',['groups'=>'post:tt']) ;
         */


        return new JsonResponse($jsonContent);

    }

}



