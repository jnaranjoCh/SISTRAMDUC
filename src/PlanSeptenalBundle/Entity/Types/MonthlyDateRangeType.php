<?php

namespace PlanSeptenalBundle\Entity\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

use PlanSeptenalBundle\ValueObject\MonthlyDateRange;

class MonthlyDateRangeType extends Type
{
    const MONTHLY_DATE_RANGE = 'monthly_date_range';

    public function getName()
    {
        return self::MONTHLY_DATE_RANGE;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        list($start, $end) = sscanf($value, '%s - %s');

        return new MonthlyDateRange($start, $end);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof MonthlyDateRange) {
            $value = $value->getStart()->format('m/Y').' - '.$value->getEnd()->format('m/Y');
        }

        return $value;
    }

    public function canRequireSQLConversion()
    {
        return false;
    }
}
