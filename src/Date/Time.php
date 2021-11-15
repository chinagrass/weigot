<?php
/**
 * Created By PhpStorm
 * User: 曹伟
 * Date: 2021/11/15
 */

namespace Weigot\Tools\Date;


class Time
{
    /**
     * 获取毫秒时间戳
     * @return int
     */
    public static function getMillisecond()
    {
        list($ms, $sec) = explode(' ', microtime());
        $msTime = (float)sprintf('%.0f', (floatval($ms) + floatval($sec)) * 1000);
        return (int)$msTime;
    }
}