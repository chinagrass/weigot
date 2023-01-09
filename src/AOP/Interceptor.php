<?php

namespace Weigot\Tools\AOP;

abstract class Interceptor
{
    public abstract function before(...$data);

    public abstract function after(...$data);

}
