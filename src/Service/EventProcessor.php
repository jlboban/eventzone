<?php

namespace App\Service;

use App\Entity\Event;
use DateTime;
use Exception;

/**
 * Class EventProcessor
 * @package App\Service
 */
class EventProcessor
{
    private const MIN_DISCOUNT = 0;

    /**
     * @var Event
     */
    private Event $event;

    /**
     * EventProcessor constructor.
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getDaysUntilEvent(): int
    {
        $currentDate = new DateTime();
        return $this->event->getStartDate()->diff($currentDate)->format('%a');
    }

    /**
     * @param int $daysMin
     * @param int $daysMax
     * @param float $percentageMin
     * @param float $percentageMax
     * @return float|int
     * @throws Exception
     */
    private function calculateDiscount(int $daysMin, int $daysMax, float $percentageMin, float $percentageMax)
    {
        $daysUntilEvent = $this->getDaysUntilEvent();

        if ($daysUntilEvent < $daysMin || $daysUntilEvent > $daysMax){
            return 0;
        }

        $rangeMapper = new RangeMapper();
        return floor($rangeMapper->map($daysUntilEvent, $daysMin, $daysMax, $percentageMin, $percentageMax));
    }

    /**
     * @return float|int
     * @throws Exception
     */
    public function getCurrentDiscount()
    {
        return $this->calculateDiscount(
            $this->event->getDiscountEnd(),
            $this->event->getDiscountBegin(),
            self::MIN_DISCOUNT,
            $this->event->getDiscount()
        );
    }

    /**
     * @return float|int
     * @throws Exception
     */
    public function getFinalPrice()
    {
        $price = $this->event->getPrice();
        $discount = $this->getCurrentDiscount();

        $x = $price * $discount / 100;
        return $price - $x;
    }
}
