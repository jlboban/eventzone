<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/settings", name="user_settings", methods={"GET"})
     * @return Response
     */
    public function settings(): Response
    {
        return $this->render('user/settings.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/reset_password", name="user_reset_password", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->getUser();
        $currentPassword = $request->request->get('currentPassword');
        $newPassword = $request->request->get('newPassword');

        $isValid = $passwordEncoder->isPasswordValid($user, $currentPassword);

        if ($isValid){
            $em = $this->getDoctrine()->getManager();
            $user->setPassword($passwordEncoder->encodePassword($user, $newPassword));
            $em->flush();

            $this->addFlash('success', 'Password changed.');
            return $this->redirectToRoute('user_settings');
        }

        $this->addFlash('error', 'Invalid current password.');
        return $this->redirectToRoute('user_settings');
    }
}
