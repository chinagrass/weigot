<?php

namespace Weigot\Tools\AOP;

interface IInterceptor
{
    public function before(...$data);

    public function after(...$data);
}
