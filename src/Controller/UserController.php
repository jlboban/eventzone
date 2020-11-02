<?php

namespace App\Controller;

use App\Form\UserSettingsType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/settings", name="user_settings", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function settings(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserSettingsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setAddress = $form->get('address')->getData();
            $user->setCity = $form->get('city')->getData();
            $user->setPostcode = $form->get('postcode')->getData();
            $user->setCountry = $form->get('country')->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully updated address.');

            return $this->redirectToRoute('user_settings');
        }

        return $this->render('user/settings.html.twig', [
            'user' => $this->getUser(),
            'form' => $form->createView(),
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
