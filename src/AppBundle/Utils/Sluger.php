<?php
/**
 * Created by PhpStorm.
 * User: lichnow
 * Date: 16/7/12
 * Time: 上午3:57
 */

namespace AppBundle\Utils;
use AppBundle\Utils\ToPingyin;


class Sluger
{
    private $pingyin;
    public function __construct(ToPingyin $pingyin)
    {
        $this->pingyin = $pingyin;
    }

    public function toPingyin($string)
    {
        return $this->pingyin->trans($string);
    }
}