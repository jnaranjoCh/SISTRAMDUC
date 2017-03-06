<?php

namespace PlanSeptenalBundle\ValueObject;

class MonthlyDateRange
{
    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;

    /**
     * @param String|\DateTime $start
     * @param String|\DateTime $end
     */
    public function __construct($start, $end)
    {
        $start = $this->buildStart($start);
        $end = $this->buildEnd($end);

        $this->checkValidity($start, $end);

        $this->start = $start;
        $this->end = $end;
    }

    protected function buildStart($start)
    {
        $start = (is_string($start)) ? \DateTime::createFromFormat('m/Y', $start) : $start;

        return $start->modify('first day of this month')->setTime(0, 0, 0);
    }

    protected function buildEnd($end)
    {
        $end = (is_string($end)) ? \DateTime::createFromFormat('m/Y', $end) : $end;

        return $end->modify('last day of this month')->setTime(23, 59, 59);
    }

    private function checkValidity($start, $end)
    {
        if ($start > $end) {
            throw new \Exception('The ending month must be after or equals to starting month', 100);
        }
    }

    public function overlaps(MonthlyDateRange $subject)
    {
        return ! $this->isDisjoint($subject);
    }

    public function isDisjoint(MonthlyDateRange $subject)
    {
        return ($this->end < $subject->start || $this->start > $subject->end);
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return clone $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return clone $this->end;
    }

    public function __toString()
    {
        return $this->getStart()->format('m/Y').' - '.$this->getEnd()->format('m/Y');
    }
}
