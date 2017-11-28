<?php
    use ON\Alias;
use PHPUnit\Framework\TestCase;

class AliasTest extends TestCase
{
    public function testAddAlias()
    {
        Alias::addAlias('b64encode', 'base64_encode');
        $result = b64encode('testAddAlias');
        $this->assertEquals(base64_encode('testAddAlias'), $result);
    }

    public function testLoadAlias()
    {
        try {
            Alias::loadAlias('Logs');
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }
}
