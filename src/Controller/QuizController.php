<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Entity\QuestionJson;
use App\Entity\QuestionQuiz;
use App\Entity\Quiz;
use App\Entity\ReponseQuestion;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/quiz")
 */
class QuizController extends AbstractController
{
    /**
     * @Route("/", name="app_quiz")
     */
    public function index(): Response
    {
        return $this->render('quiz/CreeQuiz.html.twig', [
        ]);
    }
    /**
     * @Route("/add/{idLivre}", name="add_quiz")
     */
    public function add(Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager ,int $idLivre): Response
    {
        $content=$request->getContent();
        $data = $serializer->decode($content, JsonEncoder::FORMAT);
        dump($data['quiz']);
        $quiz=new Quiz();
        $l=$entityManager->getRepository(Livre::class)->find($idLivre);
        $quiz->setTitre($data["quiz"]["titre"]);
        $quiz->setDescription($data["quiz"]["Description"]);
        $quiz->setIdLivre($l);
        $entityManager->persist($quiz);
        $entityManager->flush();

        $questions=$data["questions"];
            foreach ($questions as $q){
                $question=new QuestionQuiz();
                $question->setTitre($q["question"]);
                $question->setIdQuiz($quiz);
                $entityManager->persist($question);
                $entityManager->flush();
                $choix=$q['choix'];

                foreach ($choix as $c){
                    $reponse=new ReponseQuestion();
                    $reponse->setEtat($c["etat"]);
                    $reponse->setText($c["text"]);
                    $reponse->setIdQuestion($question);
                    $entityManager->persist($reponse);

                }
                $entityManager->flush();
            }

        return $this->render('quiz/CreeQuiz.html.twig', [
            'controller_name' => 'QuizController',
        ]);
    }




    /**
     * @Route("/fetch/{idQuiz}", name="fetch_quiz")
     */
    public function fetchQuiz(Request $request,SerializerInterface $serializer,EntityManagerInterface $entityManager,int $idQuiz): Response
    {

        $quiz=$entityManager->getRepository(Quiz::class)->find($idQuiz);
        $questions=$entityManager->getRepository(QuestionQuiz::class)->findBy(array('idQuiz' => $idQuiz));

        $arrayQuestion=array();
         foreach ($questions as $q){
             $qJson=new QuestionJson();
             $qJson->question=$q;
             $qJson->choix=$entityManager->getRepository(ReponseQuestion::class)->findBy(array('idQuestion' => $q->getId()));
             array_push($arrayQuestion,$qJson);
         }
        $array=array("quiz"=>$quiz,
            "questions"=>$arrayQuestion
        );
         dump($array);
        /*$json=$serializer->serialize($array,'json');
        return new JsonResponse($json, Response::HTTP_OK, [], true);*/
         return $this->render('quiz/afficherQuiz.html.twig', [
        'data' => $array,
    ]);

    }


}
