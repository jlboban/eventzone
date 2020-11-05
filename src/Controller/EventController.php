<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventMusicianRepository;
use App\Repository\EventRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/events")
 */
class EventController extends AbstractController
{
    private const IMAGE_PATH = "img/events/";

    /**
     * @Route("/", name="event_index", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function index(EventRepository $eventRepository): Response
    {


        return $this->render('event/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index", name="admin_event_index", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminIndex(EventRepository $eventRepository): Response
    {
        return $this->render('admin/event/index.html.twig', [
            'events' => $eventRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();

            if ($image)
            {
                $imageFileName = $fileUploader->upload($image, 'events');
                $event->setImage($imageFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Successfully created new event.');

            return $this->redirectToRoute('event_new');
        }

        return $this->render('admin/event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     * @param Event $event
     * @return Response
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/show/{id}", name="admin_event_show", methods={"GET"})
     * @param Event $event
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminShow(Event $event): Response
    {
        return $this->render('admin/event/show.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @param FileUploader $fileUploader
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Event $event, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $image = $form->get('image')->getData();
            $oldImage = $event->getImage();

            if ($image !== null) {
                $imageFileName = $fileUploader->upload($image, 'events');
                $event->setImage($imageFileName);

                if (file_exists(self::IMAGE_PATH.$oldImage)) {
                    unlink(self::IMAGE_PATH.$oldImage);
                }
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('admin_event_index');
        }

        return $this->render('admin/event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     * @param Request $request
     * @param Event $event
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {

            $image = $event->getImage();

            if (file_exists(self::IMAGE_PATH.$image)) {
                unlink(self::IMAGE_PATH.$image);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_event_index');
    }
}
