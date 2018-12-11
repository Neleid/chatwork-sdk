<?php

use Neleid\ChatworkSDK\ChatworkBase;

class ChatworkBaseTest extends ChatworkTestBase
{
    public function testConstructor()
    {
        $chatworkBase = new ChatworkBase('random_string');
        $this->assertEquals(get_class($chatworkBase), 'Neleid\ChatworkSDK\ChatworkBase');
    }
}
