<?php

namespace App\Controller;

use App\Entity\Login;
use App\Entity\Utilisateur;
use App\Form\NewPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\LoginRepository;
use App\Services\UserService;
use Exception;
use Monolog\Handler\Curl\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
class SecurityController extends AbstractController
{


    /**
     * @Route("/security", name="app_security")
     */
    public function index(): Response
    {

        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/signin", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/forgotpassword", name="app_forgotpassword")
     */
    public function forgotPassword(Request $request, \Swift_Mailer $mailer,ParameterBagInterface $params) : Response
    {

        $utilisateurRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Utilisateur::class);
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            $donnees = $form->getData();
            if(strlen($donnees['email_login'])!=8)
            {
                $this->addFlash('danger', 'Numero de telephone non valide');
                return $this->redirectToRoute('app_forgotpassword');
            }
            dump($donnees['email_login']);

            $Utilisateur = $utilisateurRepository->findOneBy(['numero_utilisateur'=>$donnees['email_login']]);
            dump($Utilisateur);
            if (!$Utilisateur) {
                //on envoie un msg flash
                $this->addFlash('danger', 'cette Numero n\'existe pas');
                return $this->redirectToRoute('app_forgotpassword');
            }
            else{
                $cache = new FilesystemAdapter();
                $code = $cache->getItem('codeV');
                $mail = $cache->getItem('mail');
                $code->set(mt_rand(1111,9999));
                $mail->set($donnees['email_login']);
                $cache->save($code);
                $cache->save($mail);
                $productsCount = $cache->getItem('codeV')->get();
               // $tempcode=mt_rand(1111,9999);
                dump($productsCount);
//                $message = (new \Swift_Message('You Got Mail!'))
//                    ->setFrom('SouthSideLabLibro@gmail.com')
//                    ->setTo($donnees['email_login'])
//                    ->setBody(
//                        "<p>Bonjour,</p><p>Une demande de reinitialisation de mot de passe a ete effectuee pour le
//                        site Libro . Votre code de verification est :  $productsCount </p>",
//                        'text/html'
//                    )
//                ;
//                $mailer->send($message);
                $messageBird = new \MessageBird\Client('jMFteV5RC1aWpdPqiq8l7XvOj'); //live
               //$messageBird = new \MessageBird\Client('RzGn34mazoxaIZnKnbNUYFI3d'); //test

                $message = new \MessageBird\Objects\Message();
                try {

                    $message->originator = '+21653788770';
                    $message->recipients = '+216'.$donnees['email_login'];
                    $message->body = 'Une demande de reinitialisation de mot de passe a ete effectuee pour le
//                        site Libro . Votre code de verification est : '. $productsCount;
                $response = $messageBird->messages->create($message);


                dump($response);
            } catch (Exception $e) {
                    echo $e;
                }
                return $this->redirectToRoute('app_newpassword');
            }



        }
       return $this->render('security/forgetpassword.html.twig', [
           'form' => $form->createView(),
       ]);
    }

    /**
     * @Route("/newpassword", name="app_newpassword")
     */
    public function newPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder ) : Response
    {


        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);
        $cache = new FilesystemAdapter();
        $tempcode=$cache->getItem('codeV')->get();
        $tempmail=$cache->getItem('mail')->get();
        $mail=$this->getDoctrine()->getManager()->getRepository(Utilisateur::class)->findOneBy(['numero_utilisateur'=>$tempmail])->getEmailUtilisateur();
        $currentUser = $this->getDoctrine()->getManager()->getRepository(Login::class)->findOneBy(['emailLogin'=>$mail]);
        if ($form->isSubmitted() ) {
            dump($tempcode);
            $donnees = $form->getData();
            if($donnees['code']!=$tempcode)
            {
                $this->addFlash('danger', 'Code incorrect');
                return $this->render('security/newpassword.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            else
            {
                $currentUser->setMdpLogin($passwordEncoder->encodePassword($currentUser, $donnees['mdpLogin']));
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($currentUser);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
            }


        }
        return $this->render('security/newpassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
