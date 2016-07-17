<?php
/**
 * Created by PhpStorm.
 * User: lichnow
 * Date: 16/7/11
 * Time: 上午9:51
 */

namespace AppBundle\Utils;
use Overtrue\Pinyin\Pinyin;

class ToPingyin
{
    private $pingyin;
    public function __construct()
    {
        $this->pingyin = new Pinyin();
    }
    public function trans($content)
    {
        return implode('',$this->pingyin->convert($content));
    }
}