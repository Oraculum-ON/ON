<?php
    use Oraculum\On;
use PHPUnit\Framework\TestCase;

class ONTest extends TestCase
{
    public function testApp()
    {
        try {
            $app = @On::App();
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    public function testCliApp()
    {
        try {
            $app = On::CliApp();
        } catch (InvalidArgumentException $notExpected) {
            $this->fail();
        }
        $this->assertTrue(true);
    }
}
