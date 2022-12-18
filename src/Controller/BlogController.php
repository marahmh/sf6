<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\CommentaireBlog;
use App\Entity\Utilisateur;
use App\Form\BlogType;
use App\Form\CommentaireBlogType;
use App\Repository\BlogRepository;
use App\Repository\CommentaireBlogRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Vangrg\ProfanityBundle\Storage\ProfanitiesStorageDefault;
use Vangrg\ProfanityBundle\VangrgProfanityBundle;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Vangrg\ProfanityBundle\Validator\Constraints as ProfanityAssert;
use Symfony\Component\Validator\Constraints as Assert;


class BlogController extends AbstractController
{
    /**
     * @var BlogRepository
     */
    private $repository ;

    public function __construct(BlogRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * @Route("/user/blog", name="app_front_blog")
     *
     */
    public function listfrontblog(): Response
    {

        $blog = $this->repository->findAll();
        return $this->render('blog/affichageblog.html.twig',compact('blog'));
    }
    /**
     * @param BlogRepository $repblog
     * @param $id
     * @return Response
     * @Route ("/blogdetail/{id}",name="app_detail_blog")
     * @ProfanityAssert\ProfanityCheck
     */
    function detail(BlogRepository $repblog,$id,Request $request,CommentaireBlogRepository $repCom,ProfanitiesStorageDefault $r,EntityManagerInterface $entityManager){
        $blogid=$repblog->find($id);


        $com=new CommentaireBlog();

        $form=$this->createForm(CommentaireBlogType::class,$com);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user=$entityManager->getRepository(Utilisateur::class)->findBy(array('idUtilisateur' =>$this->getUser()->getUsername()));

            $com->setIdUtilisateur($user[0]);

            $com->setIdBlog($blogid);
            $gddgd=$r->getProfanities();
            $com->setDateCommentaire(new \DateTime());
            $comment=$com->getContenuCommentaire();
            function filterBadwords($comment, array $gddgd, $replaceChar = '*') {
                return preg_replace_callback(
                    array_map(function($w) { return '/\b' . preg_quote($w, '/') . '\b/i'; }, $gddgd),
                    function($match) use ($replaceChar) { return str_repeat($replaceChar, strlen($match[0])); },
                    $comment
                );
            }
            $com->setContenuCommentaire(filterBadwords($comment,$gddgd,'*'));
            $em=$this->getDoctrine()->getManager();

            $em->persist($com);
            $em->flush();
            return $this->redirectToRoute('app_detail_blog',['id' => $blogid->getIdBlog()]);

        }


        $blog=$repblog->find($blogid->getIdBlog());
        $commentaire=$repCom->listComByBlog($blogid->getIdBlog());


        return $this->render("blog/details.html.twig",
            [
                'blog'=>$blog, 'commentaire'=>$commentaire ,'form'=>$form->createView()
            ]);

    }

    /**
     * @Route("/admin/blog", name="app_back_blog")
     *
     */
    public function listblog(): Response
    {

        $blog = $this->repository->findAll();
        return $this->render('blog/index.html.twig',compact('blog'));
    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("admin/addblog", name="app_addblog")
     */
    function Add(Request $request, EntityManagerInterface $entityManager): Response{
        $blog=new Blog();
        $form=$this->createForm(BlogType::class,$blog);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           // $blog->setIdUtilisateur($this->getUser());
            $user=$entityManager->getRepository(Utilisateur::class)->findBy(array('idUtilisateur' =>$this->getUser()->getUsername()));
            $blog->setIdUtilisateur($user[0]);
            $file = $form->get('photoBlog')->getData();
            $uploads_directory = $this->getParameter('upload_directory');

            $filename = $blog->getTitreBlog() . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );


            // On stocke l'image dans la base de données (son nom)
            $blog->setPhotoBlog($filename);
            $blog->setDateBlog(new \DateTime());
            $blog->setDislikeBlog(0);
            $blog->setLikeBlog(0);
            $em=$this->getDoctrine()->getManager();

            $em->persist($blog);
            $em->flush();
            return $this->redirectToRoute('app_back_blog');

        }
        return $this->render('blog/addblog.html.twig',
            [
                'blog' => $blog,
                'form'=>$form->createView()]);

    }
    /**
     * @param BlogRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route ("admin/updateblog/{id}", name="app_modifierblog")
     */
    function Update(BlogRepository $repository,$id,Request $request)
    {
        $blog = $repository->find($id);
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('photoBlog')->getData();
            if ($file != null) {
                $uploads_directory = $this->getParameter('uploads_directory');

                $filename = $blog->getTitreBlog() . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );


                $blog->setPhotoBlog($filename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("app_back_blog");
        }
        return $this->render('blog/editblog.html.twig',
            [
                'form' => $form->createView()
            ]);
    }

    /**
     * @param $id
     * @param BlogRepository $rep
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("vendeur/delete/{id}",name="app_supprimerblog")
     */
    function delete($id,BlogRepository $rep)
    {
        $blog=$rep->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($blog);
        $em->flush();
        return $this->redirectToRoute('app_back_blog');
    }
    /**
     * @param BlogRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("user/likeblog/{id}", name="app_likeblog")
     */
    function Like(BlogRepository $repository,$id,Request $request)
    {
        $blog = $repository->find($id);

        $blog->setLikeBlog($blog->getLikeBlog()+1);

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute("app_front_blog");

    }
    /**
     * @param BlogRepository $repository
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("user/dislike/{id}", name="app_dislikeblog")
     */
    function Dislike(BlogRepository $repository,$id,Request $request)
    {
        $blog = $repository->find($id);

        $blog->setDislikeBlog($blog->getDislikeBlog()+1);

        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute("app_front_blog");

    }

}
