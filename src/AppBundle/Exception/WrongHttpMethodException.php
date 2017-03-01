<?php

namespace AppBundle\Exception;

class WrongHttpMethodException extends \Exception
{
    public function __construct($method)
    {
        parent::__construct(sprintf('Method %s is not allowed', $method));
    }
}
