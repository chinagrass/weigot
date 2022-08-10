<?php

namespace Weigot\Tools\AOP;

use Weigot\Tools\Exception\AOPException;

class DynamicProxy
{
    protected $object;

    public function __construct(object $object)
    {
        $this->object = $object;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    public function __call($name, $arguments)
    {
        $object = $this->getObject();
        if (!method_exists($object, $name)) {
            $objectName = get_class($object);
            throw new AOPException("{$objectName} 对象不存在 {$name} 方法");
        }
        // @do 插入方法
        if (property_exists($object, 'interceptors')) {
            $interceptors = $object->interceptors;
            if (is_string($interceptors)) {
                $interceptors = [
                    $interceptors
                ];
            }
            $interceptorObjs = [];
            foreach ($interceptors as $interceptor) {
                $interceptorObj = new $interceptor();
                if (!($interceptorObj instanceof Interceptor)) {
                    continue;
                }
                $interceptorObjs[] = $interceptorObj;
            }
            foreach ($interceptorObjs as $interceptorObj) {
                call_user_func_array([$interceptorObj, 'before'], $arguments);
            }
            $result = call_user_func_array([$object, $name], $arguments);
            $interceptorObjs = array_reverse($interceptorObjs);
            foreach ($interceptorObjs as $interceptorObj) {
                $interceptorObj->after($result);
            }
        } else {
            $result = call_user_func_array([$object, $name], $arguments);
        }
        return $result;
    }
}
