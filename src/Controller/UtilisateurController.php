<?php

namespace App\Controller;

use App\Entity\AchatLivre;
use App\Entity\Login;
use App\Entity\profileEntity;
use App\Entity\Utilisateur;
use App\Form\ProfileEditType;
use App\Form\RegistrationType;
use App\Form\UtilisateurType;
use App\Repository\LoginRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use function PHPUnit\Framework\isNull;

/**
 * @Route("/utilisateur")
 */
class UtilisateurController extends AbstractController
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    /**
     * @Route("/k", name="app_utilisateur_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $utilisateurs = $entityManager
            ->getRepository(Utilisateur::class)
            ->findAll();

        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    /**
     * @Route("/new", name="app_utilisateur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUtilisateur}", name="app_utilisateur_show", methods={"GET"})
     */
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/{idUtilisateur}/block", name="app_utilisateur_block")
     */
    public function block(Utilisateur $utilisateur,Request $request): Response
    {
        $this->addFlash('success', 'l utilisateur à ete bloquer');
        $em=$this->getDoctrine()->getManager();
        dump($request->get('idUtilisateur'));
        $user= $em->getRepository(Utilisateur::class)->findby(array( 'idUtilisateur' => $request->get('idUtilisateur')))[0];
        dump($user);
        $log= $em->getRepository(Login::class)->findby(array('emailLogin'=>$user->getEmailUtilisateur()))[0];
        $log->setBlockedLogin(true);
        $em->persist($log);
        $em->flush();
        $utilisateurs = $em
            ->getRepository(Utilisateur::class)
            ->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    /**
     * @Route("/{idUtilisateur}/unblock", name="app_utilisateur_unblock")
     */
    public function unblock(Utilisateur $utilisateur,Request $request): Response
    {
        $this->addFlash('success', 'l utilisateur à ete bloquer');
        $em=$this->getDoctrine()->getManager();
        dump($request->get('idUtilisateur'));
        $user= $em->getRepository(Utilisateur::class)->findby(array( 'idUtilisateur' => $request->get('idUtilisateur')))[0];
        dump($user);
        $log= $em->getRepository(Login::class)->findby(array('emailLogin'=>$user->getEmailUtilisateur()))[0];
        dump($log);

        $log->setBlockedLogin(false);
        $em->persist($log);
        $em->flush();
        $utilisateurs = $em
            ->getRepository(Utilisateur::class)
            ->findAll();
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }


    /**
     * @Route("/{idUtilisateur}/edit", name="app_utilisateur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idUtilisateur}", name="app_utilisateur_delete", methods={"POST"})
     */
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getIdUtilisateur(), $request->request->get('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/", name="app_utilisateur_indexx")
     */
    public function rechercheByNiveauAction(Request $request)
    {
        dump($request->isMethod("POST"));
        $em=$this->getDoctrine()->getManager();
        $utilisateurs= $em->getRepository(Utilisateur::class)->findAll();
        if($request->isMethod("POST"))
        {
            $nom= $request->get('nomUtilisateur');


            dump($nom);

                $utilisateurs=$em->getRepository(Utilisateur::class)->createQueryBuilder('o')
                    ->Where('o.nomUtilisateur LIKE :nom ')
                    ->setParameter('nom', '%'.$nom.'%')
                    ->getQuery()
                    ->getResult();




        }
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }

    /**
     * @Route("/{idUtilisateur}/profile", name="app_profile")
     */
    public function profileloader(Utilisateur $utilisateur,EntityManagerInterface $entityManager) : Response
    {
        $livres=[];
        $achats=$entityManager->getRepository(AchatLivre::class)->findBy(array( 'idUtilisateur' => $this->getUser()->getUsername()));
        foreach ($achats as $achat)
        {
            array_push($livres,$achat->getIdLivre());
        }
        return $this->render('utilisateur/profile.html.twig', [
            'utilisateur' => $utilisateur,
            'livres'=> $livres,
        ]);
    }

    /**
     * @Route("/{idUtilisateur}/editProfile", name="app_editProfile")
     */
    public function editProfile(Utilisateur $utilisateur,Request $request,LoginRepository $loginRepository,EntityManagerInterface $entityManager) : Response
    {
        $reg= new profileEntity();
        $user = new Utilisateur();
        $login = new Login();
        $form = $this->createForm(ProfileEditType::class,$reg);

        $form->handleRequest($request);
        $livres=[];
        $achats=$entityManager->getRepository(AchatLivre::class)->findBy(array( 'idUtilisateur' => $this->getUser()->getUsername()));
        foreach ($achats as $achat)
        {
            array_push($livres,$achat->getIdLivre());
        }
        if ($form->isSubmitted() && $form->isValid()) {
            dump($reg);
            if ($reg->getAncMdpLogin()==null && $reg->getMdpLogin()==null && $reg->getConfirmeMotDePasse()==null)
            {
                dump("save no pass");
                $user=$utilisateur;

                $login=$loginRepository->findOneBy(['emailLogin' => $utilisateur->getEmailUtilisateur()]);
                $login->setEmailLogin($reg->getEmailLogin());
                //$login->setMdpLogin($this->passwordEncoder->encodePassword($reg, $reg->getMdpLogin()));
                //$login->setBlockedDuree(null);
                //$login->setBlockedLogin(false);
                //$login->setBlockedMessage("");
                //$login->setActivationToken(md5(uniqid()));
                $user->setId($utilisateur->getIdUtilisateur());
                $user->setNomUtilisateur($reg->getNomUtilisateur());
                dump($form->get('photo_utilisateur')->getData());
                if($form->get('photo_utilisateur')->getData()!=null)
                {
                    $image = $form->get('photo_utilisateur')->getData();



                    $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                    //On copie le fichier dans le dossier upload
                    $image->move(
                        $this->getParameter('upload_directory'),
                        $fichier
                    );
                    $user->setPhotoUtilisateur($fichier);
                }
                //
                //$user->setDateNaissanceUtilisateur($reg->getDateNaissanceUtilisateur());
                //$user->setTypeUtilisateur(0);
                //$user->setSoldeUtilisateur(500);
                $user->setNomUtilisateur($reg->getNomUtilisateur());
                $user->setNumeroUtilisateur($reg->getNumeroUtilisateur());
                $user->setEmailUtilisateur($reg->getEmailLogin());
                $login->setIdLogin($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($login);
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->render('utilisateur/profile.html.twig', [
                    'utilisateur' => $utilisateur,
                    'livres'=> $livres,
                ]);
            }
            if($reg->getAncMdpLogin()!=null && $reg->getMdpLogin()!=null && $reg->getConfirmeMotDePasse()!=null)
            {
                $user=$utilisateur;
                $login=$loginRepository->findOneBy(['emailLogin' => $utilisateur->getEmailUtilisateur()]);
                if($this->passwordEncoder->encodePassword($reg, $reg->getAncMdpLogin())==$login->getMdpLogin())
                {
                    dump("save change pass");
                    $login->setEmailLogin($reg->getEmailLogin());
                    $login->setMdpLogin($this->passwordEncoder->encodePassword($reg, $reg->getMdpLogin()));
                    //$login->setBlockedDuree(null);
                    //$login->setBlockedLogin(false);
                    //$login->setBlockedMessage("");
                    //$login->setActivationToken(md5(uniqid()));
                    $user->setId($utilisateur->getIdUtilisateur());
                    $user->setNomUtilisateur($reg->getNomUtilisateur());
                    dump($reg->getPhotoUtilisateur());
                    if($reg->getPhotoUtilisateur()!=null)
                    {

                        $image = $form->get('photo_utilisateur')->getData();



                        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                        //On copie le fichier dans le dossier upload
                        $image->move(
                            $this->getParameter('upload_directory'),
                            $fichier
                        );
                        $user->setPhotoUtilisateur($fichier);
                    }
                    //$user->setPhotoUtilisateur($fichier);
                    //$user->setDateNaissanceUtilisateur($reg->getDateNaissanceUtilisateur());
                    //$user->setTypeUtilisateur(0);
                    //$user->setSoldeUtilisateur(500);
                    $user->setNomUtilisateur($reg->getNomUtilisateur());
                    $user->setNumeroUtilisateur($reg->getNumeroUtilisateur());
                    $user->setEmailUtilisateur($reg->getEmailLogin());
                    $login->setIdLogin($user);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($login);
                    $entityManager->persist($user);
                    $entityManager->flush();
                    return $this->render('utilisateur/profile.html.twig', [
                        'utilisateur' => $utilisateur,
                        'livres'=> $livres,
                    ]);
                }
                else{
                    $this->addFlash('danger', 'ancien mot de passe incorrect');
                }

            }
            else{
                $this->addFlash('danger', 'veuillez remplir tous les champs');
            }
        }
        return $this->render('utilisateur/editProfile.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form->createView(),
        ]);
    }


}
