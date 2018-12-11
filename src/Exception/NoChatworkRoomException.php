<?php

namespace Neleid\ChatworkSDK\Exception;

class NoChatworkRoomException extends \Exception
{
    public function getName()
    {
        return 'Chatwork Room has not been set.';
    }
}
