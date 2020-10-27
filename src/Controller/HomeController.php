<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $registerForm = $this->createForm(RegistrationFormType::class, $this->getUser());

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'registrationForm' => $registerForm->createView(),
        ]);
    }
}
