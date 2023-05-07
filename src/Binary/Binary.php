<?php


namespace Weigot\Tools\Binary;


use Weigot\Tools\Exception\WGException;

class Binary
{
    public static $instance;

    private function __construct()
    {
    }

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 判断数字第几位是1
     * @param $num
     * @param $index
     * @return bool
     * @throws WGException
     */
    public function getStatusType($num, $index)
    {
        if ($index < 1) {
            throw new WGException("变量 index 不能小于1");
        }
        return ($num >> ($index - 1) & 1) == 1;
    }

    /**
     * 改变某个位上的值1或者0
     * @param $num
     * @param $index
     * @param $status
     * @return int
     * @throws WGException
     */
    public function updateStatusType($num, $index, $status)
    {
        $result = $num;
        if ($index < 1) {
            throw new WGException("变量 index 不能小于1");
        }
        // 如果数据是要求的值就直接返回
        if ($this->getStatusType($num, $index)) {
            return $result;
        }
        if ($status == 1) {
            $result = (1 << ($index - 1)) | $num;
        } else {
            $result = $num - (1 << ($index - 1));
        }
        return $result;
    }
}