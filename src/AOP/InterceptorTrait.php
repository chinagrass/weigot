<?php


namespace Weigot\Tools\AOP;


trait InterceptorTrait
{
    protected $interceptors;

    /**
     * @return mixed
     */
    public function getInterceptors()
    {
        return $this->interceptors;
    }

}