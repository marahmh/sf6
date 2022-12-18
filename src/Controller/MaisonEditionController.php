<?php


namespace App\Controller;

use App\Entity\MasionEdition;
use App\Entity\Utilisateur;
use Symfony\Component\Routing\Annotation\Route;

//use App\Entity\Reclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Constraints\Json;



class MaisonEditionController extends  AbstractController
{


    /******************Ajouter Reclamation*****************************************/
    /**
     * @Route("/addReclamation", name="add_reclamation")
     * @Method("POST")
     */

    public function ajouterReclamationAction(Request $request)
    {
        $reclamation = new MasionEdition();
        $description = $request->get("description");
        $nommaison = $request->get("nom");
        
        $adressemaison = $request->get("adresse");
        //  $photomaison = $request->query->get("photo_maison_edition");

        //$description = $request->query->get("description");
        //$objet = $request->query->get("objet");
        $em = $this->getDoctrine()->getManager();
        $date = new \DateTime('now');

        //$reclamation->setObjet($objet);
        $reclamation->setDescriptionMaisonEdition($description);
        $reclamation->setNomMaisonEdition($nommaison);
        $reclamation->setAdresseMaisonEdition($adressemaison);
        $reclamation->setPhotoMaisonEdition("test");
        // $reclamation->setDate($date);
        //$reclamation->setEtat(0);
        $tempUSer= $em->getRepository(Utilisateur::class)->find(4);

        $reclamation->setIdAdminMaisonEdition($tempUSer);
        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse($formatted);

    }

    /******************Supprimer Reclamation*****************************************/

    /**
     * @Route("/deleteReclamation", name="delete_reclamation")
     * @Method("DELETE")
     */

    public function deleteReclamationAction(Request $request) {
        $id = $request->get("id");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository(MasionEdition::class)->find($id);
        if($reclamation!=null ) {
            $em->remove($reclamation);
            $em->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Maison Edition a ete supprimee avec success.");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id maison invalide.");


    }

    /******************Modifier Reclamation*****************************************/
    /**
     * @Route("/updateReclamation", name="update_reclamation")
     * @Method("PUT")
     */
    public function modifierReclamationAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()
            ->getRepository(MasionEdition::class)
            ->find($request->get("id_maison_edition "));

        //$reclamation->setObjet($request->get("objet"));
        $reclamation->setDescriptionMaisonEdition($request->get("description_maison_edition"));
        $reclamation->setNomMaisonEdition($request->get("nom_maison_edition"));
        $reclamation->setAdresseMaisonEdition($request->get("adresse_maison_edition"));
        $reclamation->setPhotoMaisonEdition($request->get("photo_maison_edition"));

        $em->persist($reclamation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse("MaisonEdition a ete modifiee avec success.");

    }



    /******************affichage Reclamation*****************************************/

    /**
     * @Route("/displayReclamations", name="display_reclamation")
     */
    public function allRecAction()
    {

        $reclamation = $this->getDoctrine()->getManager()->getRepository(MasionEdition::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($reclamation);
        dump($reclamation);
        return new JsonResponse($formatted);

    }


    /******************Detail Reclamation*****************************************/

    /**
     * @Route("/detailReclamation", name="detail_reclamation")
     * @Method("GET")
     */

    //Detail Reclamation
    public function detailReclamationAction(Request $request)
    {
        $id = $request->get("id_maison_edition");

        $em = $this->getDoctrine()->getManager();
        $reclamation = $this->getDoctrine()->getManager()->getRepository(MasionEdition::class)->find($id);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription();
        });
        $serializer = new Serializer([$normalizer], [$encoder]);
        $formatted = $serializer->normalize($reclamation);
        return new JsonResponse($formatted);
    }


}