<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\PackJeton;
use App\Entity\Reclamation;
use App\Form\BlogType;
use App\Form\Reclamation1Type;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route ("/apipack")
 */

class packapiController extends AbstractController{
    /**
     * @Route("/all", name="alll")
     */
    public function showall(EntityManagerInterface $entityManager,NormalizerInterface $Normalizer):Response{
        $blog = $entityManager
            ->getRepository(PackJeton::class)
            ->findAll();
        $jsonContent = $Normalizer->normalize($blog);
        return new JsonResponse($jsonContent);
    }


    /**
     * @Route ("/add", name="add")
     */
    public function addjson (Request $request , NormalizerInterface $Normalizer,EntityManagerInterface $entityManager) {
        $pack = new PackJeton();
        $pack->setDescriptionPack($request->get("desc"));
        $pack->setQuantiePack($request->get("qte"));
        $pack->setPrixPack($request->get("prix"));
            $em=$this->getDoctrine()->getManager();

            $em->persist($pack);
            $em->flush();
        $jsonContent = $Normalizer->normalize($pack) ;
        return new Response(json_encode($jsonContent)) ;
    }

    /**
     * @Route ("/edit", name="editreclamation")
     */
    public function editjson (Request $request , NormalizerInterface $Normalizer,EntityManagerInterface $entityManager) {
        $pack = new PackJeton();


        $entityMa = $this->getDoctrine()->getManager();
        $pack = $entityMa->getRepository(PackJeton::class)->find($request->get("id")) ;
        $pack->setDescriptionPack($request->get("desc"));
        $pack->setQuantiePack($request->get("qte"));
        $pack->setPrixPack($request->get("prix"));


        $entityManager->persist($pack);
        $entityManager->flush();
        $jsonContent = $Normalizer->normalize($pack) ;
        return new Response(json_encode($jsonContent)) ;
    }
    /**
     * @Route("/delete/{id}", name="Deletejson")
     */
    public function deletejson(Request $request , NormalizerInterface $Normalizer,$id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $rec = $entityManager->getRepository(PackJeton::class)->find($id) ;
        $entityManager->remove($rec);
        $entityManager->flush();

        $jsonContent = $Normalizer->normalize($rec) ;
        return new Response("information deleted ".json_encode($jsonContent)) ;
    }

    /**
     * @Route("/all/{id}", name="all")
     */
    public function showjson(EntityManagerInterface $entityManager, NormalizerInterface $Normalizer, Request $request , $id ): Response
    {
        $jsonContent=array();
        $pack = $entityManager
            ->getRepository(PackJeton::class)
            ->findAll();
        $output=[];
        foreach ($pack as $plc){
            if($plc->getDescriptionPack()==$id){

                $jsonContent1 = $Normalizer->normalize($plc) ;
                array_push($jsonContent,$jsonContent1);}
        }



        return new JsonResponse($jsonContent);

    }

}