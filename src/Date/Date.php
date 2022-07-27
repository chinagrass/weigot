<?php


namespace Weigot\Tools\Date;


use Weigot\Tools\Enum\DateUnitEnum;
use Weigot\Tools\Exception\ValidateException;

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
        $firstDay = date('Y-m-01', $time);
        return date($format, strtotime("$firstDay +1 month -1 day"));
    }

    /**
     * 上一段日期
     * @param $time
     * @param string $format
     * @param int $last
     * @param string $unit
     * @return false|string
     * @throws ValidateException
     */
    public static function getPreDate($time, $format = "Y-m-d", $last = -1, $unit = DateUnitEnum::MONTH)
    {
        if (!DateUnitEnum::validate($unit)) {
            throw new ValidateException("unit error");
        }
        return date($format, strtotime("{$last} {$unit}", $time));// 上一月日期
    }

    /**
     * 获取两个时间之间的日期
     * @param $sDate
     * @param $eDate
     * @param string $format
     * @param string $unit
     * @return array
     * @throws ValidateException|\Exception
     */
    public static function getDatePeriod($sDate, $eDate, $format = "Y-m", $unit = DateUnitEnum::MONTH)
    {
        if (!DateUnitEnum::validate($unit)) {
            throw new ValidateException("unit error");
        }
        $result = [];
        $start = new \DateTime($sDate);
        $end = new \DateTime($eDate);
        // 时间间距
        $interval = \DateInterval::createFromDateString("1 {$unit}");
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
        return range($start_day, $end_day, 1);
    }

    /**
     * 获取两个时间之间的日期
     * @param $time
     * @param int $step
     * @return array
     */
    public static function getTimeSlotDays($time, $step = 1)
    {
        $endTime = $time - 3600 * 24 * $step;
        return self::getDaysInTimePeriod($time, $endTime);
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return array
     */
    public static function getDaysInTimePeriod($startTime, $endTime)
    {
        $date = range($startTime, $endTime, 3600 * 24);
        sort($date);
        foreach ($date as &$value) {
            $value = date("Ymd", $value);
        }
        return $date;
    }

    /**
     * 获取本周第一天
     * @param int $time
     * @return false|string
     */
    public static function getWeekStart($time = 0)
    {
        empty($time) && $time = time();
        $defaultDate = date("Y-m-d", $time);
        $first = 1;
        //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $w = date('w', strtotime($defaultDate));
        $weekStart = date('Y-m-d', strtotime("$defaultDate -" . ($w ? $w - $first : 6) . ' days'));
        return $weekStart;
    }

    /**
     * 获取本周最后一天
     * @param int $time
     * @return false|string
     */
    public static function getWeekEnd($time = 0)
    {
        $weekStart = self::getWeekStart($time);
        return date('Y-m-d', strtotime("$weekStart +6 days"));
    }
}