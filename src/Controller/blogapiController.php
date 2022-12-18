<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\PackJeton;
use App\Entity\Reclamation;
use App\Entity\Utilisateur;
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
 * @Route ("/apiblog")
 */

class blogapiController extends AbstractController{
    /**
     * @Route("/all")
     */
    public function showall(EntityManagerInterface $entityManager,NormalizerInterface $Normalizer):Response{
        $blog = $entityManager
            ->getRepository(Blog::class)
            ->findAll();
        $jsonContent = $Normalizer->normalize($blog);
        return new JsonResponse($jsonContent);
    }


    /**
     * @Route ("/add")
     */
    public function addjson (Request $request , NormalizerInterface $Normalizer,EntityManagerInterface $entityManager) {
        $blog=new Blog();
        $blog->setTitreBlog($request->get("titre"));
        $blog->setSujetBlog($request->get("sujet"));
        $blog->setPhotoBlog($request->get("img"));
        $blog->setDateBlog(new \DateTime());
        $blog->setDislikeBlog(0);
        $blog->setLikeBlog(0);
        $user=new Utilisateur();
        $user=$entityManager->getRepository(Utilisateur::class)->find(1);
        $blog->setIdUtilisateur($user);
        $em=$this->getDoctrine()->getManager();

        $em->persist($blog);
        $em->flush();
        $jsonContent = $Normalizer->normalize($blog) ;
        return new Response(json_encode($jsonContent)) ;
    }

    /**
     * @Route ("/edit")
     */
    public function editjson (Request $request , NormalizerInterface $Normalizer,EntityManagerInterface $entityManager) {
        $blog=new Blog();

        $entityMa = $this->getDoctrine()->getManager();
        $blog = $entityMa->getRepository(Blog::class)->find($request->get("id")) ;
        $blog->setTitreBlog($request->get("titre"));
        $blog->setSujetBlog($request->get("sujet"));
        $blog->setPhotoBlog($request->get("img"));


        $entityManager->persist($blog);
        $entityManager->flush();
        $jsonContent = $Normalizer->normalize($blog) ;
        return new Response(json_encode($jsonContent)) ;
    }
    /**
     * @Route("/delete/{id}")
     */
    public function deletejson(Request $request , NormalizerInterface $Normalizer,$id): Response
    {

        $entityManager = $this->getDoctrine()->getManager();
        $rec = $entityManager->getRepository(Blog::class)->find($id) ;
        $entityManager->remove($rec);
        $entityManager->flush();

        $jsonContent = $Normalizer->normalize($rec) ;
        return new Response("information deleted ".json_encode($jsonContent)) ;
    }

    /**
     * @Route("/all/{id}")
     */
    public function showjson(EntityManagerInterface $entityManager, NormalizerInterface $Normalizer, Request $request , $id ): Response
    {
        $jsonContent=array();
        $blog = $entityManager
            ->getRepository(Blog::class)
            ->findAll();
        $output=[];
        foreach ($blog as $plc){
            if($plc->getTitreBlog()==$id){

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