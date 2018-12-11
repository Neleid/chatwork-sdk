<?php

namespace Neleid\ChatworkSDK\Exception;

class NoChatworkApiKeyException extends \Exception
{
    public function getName()
    {
        return 'Chatwork Api Key has not been set.';
    }
}
