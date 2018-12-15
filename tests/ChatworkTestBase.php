<?php

class ChatworkTestBase extends \PHPUnit\Framework\TestCase
{
    protected function loadFixture($name)
    {
        $filePath = realpath(dirname(__FILE__)) . "/fixtures/$name.json";
        if (!file_exists($filePath)) {
            return [];
        }
        $content = file_get_contents($filePath);
        return json_decode($content, true);
    }
}
