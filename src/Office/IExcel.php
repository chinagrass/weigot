<?php
/**
 * Created By PhpStorm
 * User: 曹伟
 * Date: 2021/4/12
 */

namespace Weigot\Tools\Office;


interface IExcel
{
    public static function read($file, $ext = 'Xlsx', $offset = 1);

    public static function write();
}