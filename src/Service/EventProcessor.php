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
    // Discount percentage will be scaled based on days until event,
    // starting from and ending on values defined below.
    private const DISCOUNT_FROM = 365;
    private const DISCOUNT_TO = 30;

    private const MIN_DISCOUNT = 0;

    private Event $event;
    private float $discount;

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
        return floor($this->discount = $rangeMapper->map($daysUntilEvent, $daysMin, $daysMax, $percentageMin, $percentageMax));
    }

    public function getCurrentDiscount()
    {
        $eventDiscount = $this->event->getDiscount();
        $this->discount = $this->calculateDiscount(self::DISCOUNT_TO, self::DISCOUNT_FROM, self::MIN_DISCOUNT, $eventDiscount);
        return $this->discount;
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
