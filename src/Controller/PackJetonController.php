<?php

namespace App\Controller;

use App\Entity\AchatRecomponse;
use App\Entity\Utilisateur;
use App\Entity\PackJeton;
use App\Form\PackJetonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Stripe\Checkout\Session;
use Stripe\Stripe;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


/**
 * @Route("/pack/jeton")
 */
class PackJetonController extends AbstractController
{
    /**
     * @Route("/", name="app_pack_jeton_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $packJetons = $entityManager
            ->getRepository(PackJeton::class)
            ->findAll();

        return $this->render('pack_jeton/achat.html.twig', [
            'pack_jetons' => $packJetons,
        ]);
    }
    /**
     * @Route("/back", name="app_pack_jeton_index_back", methods={"GET"})
     */
    public function indexback(EntityManagerInterface $entityManager): Response
    {
        $packJetons = $entityManager
            ->getRepository(PackJeton::class)
            ->findAll();

        return $this->render('pack_jeton/index.html.twig', [
            'pack_jetons' => $packJetons,
        ]);
    }

    /**
     * @Route("/new", name="app_pack_jeton_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $packJeton = new PackJeton();
        $form = $this->createForm(PackJetonType::class, $packJeton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($packJeton);
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pack_jeton/new.html.twig', [
            'pack_jeton' => $packJeton,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idPack}", name="app_pack_jeton_show", methods={"GET"})
     */
    public function show(PackJeton $packJeton): Response
    {
        return $this->render('pack_jeton/show.html.twig', [
            'pack_jeton' => $packJeton,
        ]);
    }



    /**
     * @Route("/{idPack}/edit", name="app_pack_jeton_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PackJeton $packJeton, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PackJetonType::class, $packJeton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pack_jeton/edit.html.twig', [
            'pack_jeton' => $packJeton,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idPack}", name="app_pack_jeton_delete", methods={"POST"})
     */
    public function delete(Request $request, PackJeton $packJeton, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$packJeton->getIdPack(), $request->request->get('_token'))) {
            $entityManager->remove($packJeton);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recomponse_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{idPack}/acheter", name="app_jeton_acheter", methods={"GET", "POST"})
     */
    public function acheter(Request $request, PackJeton $packJeton, EntityManagerInterface $entityManager): Response
    {

        $utilisateur = new Utilisateur();

         $prix=$packJeton->getQuantiePack();
        $solde=$entityManager->getRepository(Utilisateur::class)

            ->createQueryBuilder('o')
            ->update()
            ->set('o.soldeUtilisateur','o.soldeUtilisateur + :prix')
            ->Where('o.idUtilisateur = :idusr ')
            ->setParameter('idusr', $this->getUser()->getUsername())
            ->setParameter('prix',$prix)
            ->getQuery()
            ->execute();

        //$verif= $entityManager->getRepository(ParticipationEvenement::class)->estParticipant(1,$evenement);

        Stripe::setApiKey('sk_test_51KuG2SJGwr8fgyZYKyIcnc0wQuVMDSBRctjLaj9erMUuZFhkHyXwR9NlpL8eGMCHemoXPmi0nxjcxwezUWA3CHOF00iHIMjNFa');
        $unit = number_format($packJeton->getPrixPack()/$packJeton->getQuantiePack(),1)*100;
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => $packJeton->getDescriptionPack(),
                            'images' => ['uploads/e2b5f15103b9637a7cf06fa35f84843b.png'],
                        ],
                        'unit_amount'  => $packJeton->getPrixPack()*100,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $this->generateUrl('app_pack_jeton_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'           => $this->generateUrl('app_pack_jeton_index', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        $this->addFlash('message', 'Achat effectué avec succès');

        return $this->redirect($session->url, 303);
    }

}
