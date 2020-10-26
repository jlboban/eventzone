<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Musician;
use App\Form\MusicianType;
use App\Repository\MusicianRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/musicians")
 */
class MusicianController extends AbstractController
{
    /**
     * @Route("/", name="musician_index", methods={"GET"})
     * @param MusicianRepository $musicianRepository
     * @return Response
     */
    public function index(MusicianRepository $musicianRepository): Response
    {
        return $this->render('musician/index.html.twig', [
            'musicians' => $musicianRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index", name="admin_musician_index", methods={"GET"})
     * @param MusicianRepository $musicianRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminIndex(MusicianRepository $musicianRepository): Response
    {
        return $this->render('admin/musician/index.html.twig', [
            'musicians' => $musicianRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="musician_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $musician = new Musician();
        $form = $this->createForm(MusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image)
            {
                $imageFileName = $fileUploader->upload($image, 'musicians');
                $musician->setImage($imageFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($musician);
            $entityManager->flush();

            return $this->redirectToRoute('musician_index');
        }

        return $this->render('admin/musician/new.html.twig', [
            'musician' => $musician,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="musician_show", methods={"GET"})
     * @param Musician $musician
     * @return Response
     */
    public function show(Musician $musician): Response
    {
        return $this->render('musician/show.html.twig', [
            'musician' => $musician
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_musician_show", methods={"GET"})
     * @param Musician $musician
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminShow(Musician $musician): Response
    {
        return $this->render('admin/musician/show.html.twig', [
            'musician' => $musician
        ]);
    }

    /**
     * @Route("/{id}/edit", name="musician_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Musician $musician
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Musician $musician): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(MusicianType::class, $musician);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('musician_index');
        }

        return $this->render('admin/musician/edit.html.twig', [
            'musician' => $musician,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="musician_delete", methods={"DELETE"})
     * @param Request $request
     * @param Musician $musician
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Musician $musician): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$musician->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($musician);
            $entityManager->flush();
        }

        return $this->redirectToRoute('musician_index');
    }
}
