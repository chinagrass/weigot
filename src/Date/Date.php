<?php


namespace Weigot\Tools\Date;


class Date
{
    /**
     * 获得本月最后一天日期
     * @param $time
     * @param string $format
     * @return false|string
     */
    public static function getMonthLastDate($time, $format = "Y-m-d")
    {
        return date($format, strtotime(' +1 month -1 day', $time));// 本月最后一天
    }

    /**
     * 上一月日期
     * @param $time
     * @param string $format
     * @return false|string
     */
    public static function getPreDate($time, $format = "Y-m-d")
    {
        return date($format, strtotime("-1 months", $time));// 上一月日期
    }

    /**
     * 获取两个时间之间的月份
     * @param $sDate
     * @param $eDate
     * @param string $format
     * @return array
     * @throws \Exception
     */
    public static function getDatePeriod($sDate, $eDate, $format = "Y-m")
    {
        $result = [];
        $start = new \DateTime($sDate);
        $end = new \DateTime($eDate);
// 时间间距 这里设置的是一个月
        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($start, $interval, $end);
        foreach ($period as $dt) {
            $result[] = $dt->format($format);
        }
        return $result;
    }

    /**
     * 获得某月的所有日期
     * @param string $time
     * @return array
     */
    public static function getMonthDays($time = "")
    {
        empty($time) && $time = time();
        $start_day = date('Ym01', $time);
        $end_day = date('Ymd', strtotime("{$start_day} + 1 month -1 day"));
        $date = range($start_day, $end_day, 1);
        return $date;
    }
}