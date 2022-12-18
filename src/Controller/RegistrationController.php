<?php
namespace App\Controller;

use App\Entity\Login;
use App\Entity\Utilisateur;
use App\Entity\RegisterEntity;

use App\Form\RegistrationType;
use App\Repository\LoginRepository;
use Stripe\Util\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {
        $reg= new RegisterEntity();
        $user = new Utilisateur();
        $login = new Login();

        $form = $this->createForm(RegistrationType::class,$reg);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('photo_utilisateur')->getData();



            $fichier = md5(uniqid()) . '.' . $image->guessExtension();
            //On copie le fichier dans le dossier upload
            $image->move(
                $this->getParameter('upload_directory'),
                $fichier
            );

            // Encode the new users password
            $login->setEmailLogin($reg->getEmailLogin());
            $login->setMdpLogin($this->passwordEncoder->encodePassword($reg, $reg->getMdpLogin()));
            $login->setBlockedDuree(null);
            $login->setBlockedLogin(false);
            $login->setBlockedMessage("");
            $login->setActivationToken(md5(uniqid()));

            // Set their role
            $user->setNomUtilisateur($reg->getNomUtilisateur());
            $user->setPhotoUtilisateur($fichier);
            $user->setDateNaissanceUtilisateur($reg->getDateNaissanceUtilisateur());
            $user->setTypeUtilisateur($reg->getTypeUtilisateur());
            $user->setSoldeUtilisateur(500);
            $user->setEmailUtilisateur($reg->getEmailLogin());
            $login->setIdLogin($user);

            // Save
            $em = $this->getDoctrine()->getManager();
//            echo($user->getIdUtilisateur());
//
//            echo($user->getEmailUtilisateur());
//            echo($user->getNomUtilisateur());
//            echo($user->getPhotoUtilisateur());
//            echo($user->getSoldeUtilisateur());
//            echo($user->getTypeUtilisateur());
//
//            echo($login->getEmailLogin());
//            echo($login->getIdLogin());
//            echo($login->getMdpLogin());

            $em->persist($user);
            $em->persist($login);
            $em->flush();

            $message = (new \Swift_Message('Nouveau compte'))
                // On attribue l'expéditeur
                ->setFrom('SouthSideLabLibro@gmail.com')
                // On attribue le destinataire
                ->setTo($login->getEmailLogin())
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'emails/activationMail.html.twig', ['token' => $login->getActivationToken()]
                    ),
                    'text/html'
                )
            ;
            $mailer->send($message);
            $this->addFlash('info', 'Un email d\'activation a été envoyer');
//         return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     */
    public function activation($token, LoginRepository $login)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $login = $login->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$login){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $login->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($login);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');

        // On retourne à l'accueil
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/api/signup", name="add_ajouterUtilisateur")
     * @Method("POST")
     */
    public function ajouterUtilisateurApi(Request $request, \Swift_Mailer $mailer)
    {
        $reg= new RegisterEntity();
        $user = new Utilisateur();
        $login = new Login();
        $msg=null;
//        $image=$form->get('photo')->getData();
//        $fichier = md5(uniqid()) . '.' . $image->guessExtension();
//        $image->move(
//            $this->getParameter('upload_directory'),
//            $fichier
//        );

        // login
        $login->setEmailLogin($request->get("emailLogin"));
        $login->setMdpLogin($this->passwordEncoder->encodePassword($reg,$request->get("mdpLogin")));
        $login->setBlockedDuree(null);
        $login->setBlockedLogin(false);
        $login->setBlockedMessage("");
        $login->setActivationToken(md5(uniqid()));

        // utilisateur
        $user->setNomUtilisateur($request->get("nomUtilisateur"));
//        $user->setPhotoUtilisateur($fichier);
        //$user->setDateNaissanceUtilisateur($request->get("dateNaissanceUtilisateur"));
        $user->setTypeUtilisateur(1);
        $user->setNumeroUtilisateur( (int) $request->get("numeroUtilisateur"));
        $user->setSoldeUtilisateur(500);
        $user->setEmailUtilisateur($request->get("emailLogin"));
        $login->setIdLogin($user);
        $exist=$this->getDoctrine()->getManager()
            ->getRepository(Utilisateur::class)
            ->findOneBy(array("emailUtilisateur"=>$request->get("emailLogin")));
        if ($exist)
        {
            $msg="l'utilisateur existe deja";
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize([array("response"=>$msg)]);
            return new JsonResponse($formatted);
        }
        $message = (new \Swift_Message('Nouveau compte'))
            // On attribue l'expéditeur
            ->setFrom('SouthSideLabLibro@gmail.com')
            // On attribue le destinataire
            ->setTo($login->getEmailLogin())
            // On crée le texte avec la vue
            ->setBody(
                $this->renderView(
                    'emails/activationMail.html.twig', ['token' => $login->getActivationToken()]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($login);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize([array("response"=>$msg)]);
        return new JsonResponse($formatted);

    }

   /**
     * @Route("/api/login", name="app_api_login")
    * @Method("GET")
     */
    public function apiLogin(Request $request )
    {
        $user= new Utilisateur();
        $login = new Login();
        $msg=null;
        $serializer = new Serializer([new ObjectNormalizer()]);
    $reg= new RegisterEntity();
        $mail= $request->get("emailLogin");
        $mdp = $this->passwordEncoder->encodePassword($reg,$request->get("mdpLogin"));
        dump($mail);
        dump($mdp);
        $login = $this->getDoctrine()->getManager()
            ->getRepository(Login::class)
            ->findOneBy(array("emailLogin"=>$mail));
        if($login)
        {
            if ($login->getMdpLogin()==$mdp)
            {
                $user=$this->getDoctrine()->getManager()
                    ->getRepository(Utilisateur::class)
                    ->findOneBy(array("emailUtilisateur"=>$mail));
            }
            else{
                $msg="Verifier les données";
            }

        }
        else{
            $msg="L'utilisateur n'existe pas";
        }
        $formatted = $serializer->normalize([array("login"=>$login,"utilisateur"=>$user,"response"=>$msg)]);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/api/activate", name="add_activateUtilisateur")
     * @Method("POST")
     */
    public function activateApi(Request $request, \Swift_Mailer $mailer)
    {
        $msg=null;
        $usermail=$request->get("emailLogin");
        $activetoken=$request->get("activationToken");

        $exist=$this->getDoctrine()->getManager()
            ->getRepository(Utilisateur::class)
            ->findOneBy(array("emailUtilisateur"=>$request->get("emailLogin")));
        if (!$exist)
        {
            $msg="l'utilisateur n'existe pas";
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize([array("response"=>$msg)]);
            return new JsonResponse($formatted);
        }
        $message = (new \Swift_Message('Nouveau compte'))
            // On attribue l'expéditeur
            ->setFrom('SouthSideLabLibro@gmail.com')
            // On attribue le destinataire
            ->setTo($usermail)
            // On crée le texte avec la vue
            ->setBody(
                $this->renderView(
                    'emails/activationApi.html.twig', ['token' => $activetoken]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize([array("response"=>$msg)]);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/activationApi/{token}", name="activationApi")
     */
    public function activationApi($token, LoginRepository $login)
    {
        // On recherche si un utilisateur avec ce token existe dans la base de données
        $login = $login->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if(!$login){
            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $login->setActivationToken(null);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($login);
        $entityManager->flush();


        return $this->render('emails/active.html.twig');
    }
}