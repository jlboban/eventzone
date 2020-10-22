<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Venue;
use App\Form\VenueType;
use App\Repository\VenueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/venue")
 */
class VenueController extends AbstractController
{
    /**
     * @Route("/", name="venue_index", methods={"GET"})
     * @param VenueRepository $venueRepository
     * @return Response
     */
    public function index(VenueRepository $venueRepository): Response
    {
        return $this->render('venue/index.html.twig', [
            'venues' => $venueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="venue_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $venue = new Venue();
        $form = $this->createForm(VenueType::class, $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image)
            {
                $imageFileName = $fileUploader->upload($image, 'venues');
                $venue->setImage($imageFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($venue);
            $entityManager->flush();

            return $this->redirectToRoute('venue_index');
        }

        return $this->render('venue/new.html.twig', [
            'venue' => $venue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="venue_show", methods={"GET"})
     * @param Venue $venue
     * @return Response
     */
    public function show(Venue $venue): Response
    {
        return $this->render('venue/show.html.twig', [
            'venue' => $venue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="venue_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Venue $venue
     * @return Response
     */
    public function edit(Request $request, Venue $venue): Response
    {
        $form = $this->createForm(VenueType::class, $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('venue_index');
        }

        return $this->render('venue/edit.html.twig', [
            'venue' => $venue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="venue_delete", methods={"DELETE"})
     * @param Request $request
     * @param Venue $venue
     * @return Response
     */
    public function delete(Request $request, Venue $venue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$venue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($venue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('venue_index');
    }
}
