<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Venue;
use App\Form\VenueType;
use App\Repository\VenueRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/venues")
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
     * @Route("/index", name="admin_venue_index", methods={"GET"})
     * @param VenueRepository $venueRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminIndex(VenueRepository $venueRepository): Response
    {
        return $this->render('admin/venue/index.html.twig', [
            'venues' => $venueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="venue_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @IsGranted("ROLE_ADMIN")
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

        return $this->render('admin/venue/new.html.twig', [
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
            'venue' => $venue
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_venue_show", methods={"GET"})
     * @param Venue $venue
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminShow(Venue $venue): Response
    {
        return $this->render('admin/venue/show.html.twig', [
            'venue' => $venue
        ]);
    }

    /**
     * @Route("/{id}/edit", name="venue_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Venue $venue
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Venue $venue): Response
    {
        $form = $this->createForm(VenueType::class, $venue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('venue_index');
        }

        return $this->render('admin/venue/edit.html.twig', [
            'venue' => $venue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="venue_delete", methods={"DELETE"})
     * @param Request $request
     * @param Venue $venue
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Venue $venue): Response
    {
        if ($this->isCsrfTokenValid('delete'.$venue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($venue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_venue_index');
    }
}
