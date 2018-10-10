<?php
    use Oraculum\Crypt;
use PHPUnit\Framework\TestCase;

class CryptTest extends TestCase
{
    public function testCrypt()
    {
        $str = Crypt::strCrypt('testCrypt');
        $result = Crypt::strDecrypt($str);
        $this->assertEquals('testCrypt', $result);
    }

    public function testBlowfish()
    {
        $str = Crypt::blowfish('testBlowfish1');
        $result = Crypt::blowfishCheck('testBlowfish1', $str);
        $this->assertTrue($result);
    }

    public function testBlowfishCusto()
    {
        $str = Crypt::blowfish('testBlowfish2', 13);
        $result = Crypt::blowfishCheck('testBlowfish2', $str);
        $this->assertTrue($result);
    }
}
