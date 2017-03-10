<?php

namespace PlanSeptenalBundle\ValueObject;

class MonthlyDateRange
{
    const FORMAT = 'm/Y';

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
        $start = (is_string($start)) ? \DateTime::createFromFormat(self::FORMAT, $start) : $start;

        return $start->modify('first day of this month')->setTime(0, 0, 0);
    }

    protected function buildEnd($end)
    {
        $end = (is_string($end)) ? \DateTime::createFromFormat(self::FORMAT, $end) : $end;

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

    public function getStartAsString()
    {
        return $this->getStart()->format(self::FORMAT);
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return clone $this->end;
    }

    public function getEndAsString()
    {
        return $this->getEnd()->format(self::FORMAT);
    }

    public function __toString()
    {
        return $this->getStart()->format(self::FORMAT).' - '.$this->getEnd()->format(self::FORMAT);
    }
}
