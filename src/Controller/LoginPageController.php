<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class LoginPageController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
        $user = $this->security->getUser();
        echo $user->getUsername();
    }

    public function indexAction()
    {

        echo $this->getUser();
        // ...
    }

    /**
     * @Route("/loginpage", name="app_login_page")
     */
    public function index(): Response
    {
        return $this->render('login_page/index.html.twig', [
            'controller_name' => 'LoginPageController',
        ]);
    }


}
