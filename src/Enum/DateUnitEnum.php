<?php


namespace Weigot\Tools\Enum;


class DateUnitEnum
{
    const MONTH = 'month';
    const DAY = 'day';
    const DAYS = 'days';
    const MONTHS = 'months';

    /**
     * 验证
     * @param $unit
     * @return bool
     */
    public static function validate($unit)
    {
        return in_array($unit, self::all());
    }

    public static function all()
    {
        return [
            self::MONTH,
            self::DAY,
            self::DAYS,
            self::MONTHS
        ];
    }
}