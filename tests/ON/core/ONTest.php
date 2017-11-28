<?php
    use ON\ON;
use PHPUnit\Framework\TestCase;

class ONTest extends TestCase
{
    public function testApp()
    {
        try {
            $app = @ON::App();
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    public function testCliApp()
    {
        try {
            $app = ON::CliApp();
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }
}
