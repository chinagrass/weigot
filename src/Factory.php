<?php


namespace Weigot\Tools;


use Weigot\Tools\AOP\DynamicProxy;

class Factory
{
    /**
     * @param object $object
     * @return DynamicProxy
     */
    public static function aop(object $object)
    {
        return new DynamicProxy($object);
    }
}