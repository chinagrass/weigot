<?php


namespace Weigot\Tools;


use Weigot\Tools\AOP\DynamicProxy;

class Factory
{
    /**
     * @param Object $object
     * @return DynamicProxy
     */
    public static function aop(Object $object)
    {
        return new DynamicProxy($object);
    }
}