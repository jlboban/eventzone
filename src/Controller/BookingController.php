<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Event;
use App\Service\EventProcessor;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/booking/new/{id}", name="booking_new", methods={"POST"})
     * @param Event $event
     * @return Response
     * @throws Exception
     */
    public function new(Event $event): Response
    {
        $booking = new Booking();
        $booking->setUser($this->getUser());
        $booking->setEvent($event);

        $eventProcessor = new EventProcessor($event);

        $booking->setFinalPrice($eventProcessor->getFinalPrice());

        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();

        return $this->redirectToRoute("event_show", ['id' => $event->getId()]);
    }
}
