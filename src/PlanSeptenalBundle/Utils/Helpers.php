<?php

namespace PlanSeptenalBundle\Utils;

class Helpers
{
    public static function filterKeys(array $target, array $whitelist)
    {
        return array_intersect_key($target, array_flip($whitelist));
    }
}