<?php


namespace Weigot\Tools\Exception;

use Throwable;

class WGException extends \Exception
{
    private $info = '';

    /**
     * WGException constructor.
     * @param string $message
     * @param array $variableValues
     * @param array $info
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $variableValues = [], $info = [], Throwable $previous = null)
    {
        $errorInfo = explode('_', $message, 2);
        if (count($errorInfo) < 2) {
            parent::__construct($errorInfo[0]);
            return;
        }
        $code = $errorInfo[0];
        $msg = $errorInfo[1];
        if (!empty($variableValues)) {
            $msg = strtr($msg, $variableValues);
        }

        $this->info = $info;
        parent::__construct($msg, $code, $previous);
    }

    public function getInfo()
    {
        return $this->info;
    }
}