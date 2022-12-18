<?php

namespace App\Controller;

use App\Entity\CommentaireBlog;
use App\Form\CommentaireBlogType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireBlogRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaireBlogController extends AbstractController
{
    /**
     * @var CommentaireBlogRepository
     */
    private $repository ;

    public function __construct(CommentaireBlogRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @Route("/commentaire/blog", name="app_commentaire_blog")
     */
    public function index(): Response
    {
        return $this->render('commentaire_blog/index.html.twig', [
            'controller_name' => 'CommentaireBlogController',
        ]);
    }

    /**
     ** @param Request $request
     * @Route("user/editcomment/{id}",name="updatecomment")
     */

    function Update(CommentaireBlogRepository $repository,$id,Request $request)
    {
        $com=new CommentaireBlog();
        $com = $repository->find($id);
        $form = $this->createForm(CommentaireBlogType::class, $com);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('app_detail_blog',['id' => $com->getIdBlog($id)->getIdBlog() ]);

        }
        return $this->render('/commentaire_blog/editcomment.html.twig',
            [
                'form' => $form->createView(),
            ]);
    }
    /**
     * @param $id
     * @param CommentaireBlogRepository $rep
     * @Route ("user/deletecomm/{id}", name="app_delete_comm")
     */
    function Delete($id,CommentaireBlogRepository $rep,BlogRepository $repository){
        $re=$repository->find($id);
        $com=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($com);
        $em->flush();
        return $this->redirectToRoute('app_detail_blog',['id' => $com->getIdBlog($id)->getIdBlog() ]);

    }
    /**
     * @Route("/admin/commentaires", name="app_back_commentaire")
     *
     */
    public function listcommentaire(): Response
    {

        $commentaire = $this->repository->findAll();
        return $this->render('commentaire_blog/index.html.twig',compact('commentaire'));
    }
    /**
     * @param $id
     * @param CommentaireBlogRepository $rep
     * @Route ("admin/deletecomm/{id}", name="app_delete_comm_back")
     */
    function Deleteback($id,CommentaireBlogRepository $rep,BlogRepository $repository){
        $re=$repository->find($id);
        $com=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($com);
        $em->flush();
        return $this->redirectToRoute('app_back_commentaire');

    }
}
