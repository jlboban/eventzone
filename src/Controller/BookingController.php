<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Event;
use App\Repository\BookingRepository;
use App\Service\EventProcessor;
use Dompdf\Dompdf;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/booking")
 */
class BookingController extends AbstractController
{
    private const CANCEL_BOOKING_DAYS = 7;

    /**
     * @Route("/new/{id}", name="booking_new", methods={"POST"})
     * @param Event $event
     * @return Response
     * @throws Exception
     * @IsGranted("ROLE_USER")
     */
    public function new(Event $event): Response
    {
        $booking = new Booking();
        $user = $this->getUser();

        if ($user){
            $booking->setUser($this->getUser());
            $booking->setEvent($event);

            $eventProcessor = new EventProcessor($event);

            $booking->setFinalPrice($eventProcessor->getFinalPrice());

            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush();

            return $this->redirectToRoute("booking_show", [
                'id' => $booking->getEvent()->getId()
            ]);
        }
    }

    /**
     * @Route("/show/{id}", name="booking_show", methods={"GET"})
     * @param Event $event
     * @param BookingRepository $bookingRepository
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function show(Event $event, BookingRepository $bookingRepository): Response
    {
        $user = $this->getUser();
        $hasBillingAddress = $user->hasBillingAddress();
        $userBooking = $bookingRepository->findByUser($user);
        $isBooked = $bookingRepository->isUserBooked($user);
        $isBookedToEvent = $bookingRepository->isUserBookedToEvent($user, $event);

        $eventProcessor = new EventProcessor($event);
        $currentDiscount = $eventProcessor->getCurrentDiscount();
        $finalPrice = $eventProcessor->getFinalPrice();
        $isCancellable = $eventProcessor->getDaysUntilEvent() <= self::CANCEL_BOOKING_DAYS ? false : true;

        return $this->render('booking/show.html.twig', [
            'user' => $user,
            'event' => $event,
            'currentDiscount' => $currentDiscount,
            'finalPrice' => $finalPrice,
            'userBooking' => $userBooking,
            'isBooked' => $isBooked,
            'isBookedToEvent' => $isBookedToEvent,
            'isCancellable' => $isCancellable,
            'hasBillingAddress' => $hasBillingAddress,
        ]);
    }

    /**
     * @Route("/{id}/pdf", name="booking_show_pdf", methods={"GET", "POST"})
     * @param Event $event
     * @param BookingRepository $bookingRepository
     * @return Response
     * @IsGranted("ROLE_USER")
     * @throws Exception
     */
    public function showPdf(Event $event, BookingRepository $bookingRepository): Response
    {
        $user = $this->getUser();
        $hasBillingAddress = $user->hasBillingAddress();
        $userBooking = $bookingRepository->findByUser($user);

        $eventProcessor = new EventProcessor($event);
        $currentDiscount = $eventProcessor->getCurrentDiscount();
        $finalPrice = $eventProcessor->getFinalPrice();

        $html = $this->renderView('booking/showPdf.html.twig', [
            'user' => $user,
            'event' => $event,
            'currentDiscount' => $currentDiscount,
            'finalPrice' => $finalPrice,
            'userBooking' => $userBooking,
            'hasBillingAddress' => $hasBillingAddress,
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');

        $options = $dompdf->getOptions();
        $options->setIsHtml5ParserEnabled('true');

        $dompdf->setOptions($options);
        $dompdf->render();

        return new Response($dompdf->stream());
    }

    /**
     * @Route("/{id}", name="booking_delete", methods={"DELETE"})
     * @param Request $request
     * @param Booking $booking
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute("booking_show", ['id' => $booking->getEvent()->getId()]);
    }
}
