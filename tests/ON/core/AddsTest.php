<?php
    use ON\Adds;
use PHPUnit\Framework\TestCase;

class AddsTest extends TestCase
{
    public function testLoad()
    {
        try {
            Adds::load('datagrid');
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    public function testLoadError()
    {
        $this->expectException('ON\Exception');
        Adds::load('not-found');
    }
}
