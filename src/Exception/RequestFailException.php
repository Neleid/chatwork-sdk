<?php

namespace Neleid\ChatworkSDK\Exception;

class RequestFailException extends \Exception
{
    public function getName()
    {
        return 'Request fail';
    }
}
