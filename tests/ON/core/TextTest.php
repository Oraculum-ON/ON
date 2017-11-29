<?php
    use PHPUnit\Framework\TestCase;
 
class TextTest extends TestCase
{
    public function testMoeda()
    {
        define('MOEDA', 'R$');
        $text = new ON\Text();
        $result = $text->moeda(1.23);
        $this->assertEquals('R$ 1,23', $result);
 
        $result = $text->moeda(1.23, false);
        $this->assertEquals('1,23', $result);
    }
     
    public function testMoedaSql()
    {
        $result = ON\Text::moedaSql('1,23');
        $this->assertEquals('1.23', $result);
    }
 
    public function testData()
    {
        $result = ON\Text::data('1903-02-01');
        $this->assertEquals('01/02/1903', $result);
    }
     
    public function testDataNull()
    {
        $result = ON\Text::data(null, false);
        $this->assertNull($result);
    }
     
    public function testDataNotNull()
    {
        $result = ON\Text::data();
        $this->assertEquals(date('d/m/Y'), $result);
    }
 
    public function testDataSql()
    {
        $result = ON\Text::dataSql('01/02/1903');
        $this->assertEquals('1903-02-01', $result);
    }
 
    public function testDataSqlNotNull()
    {
        $result = ON\Text::dataSql();
        $this->assertEquals(date('Y-m-d'), $result);
    }
 
    public function testDataSqlNull()
    {
        $result = ON\Text::dataSql(null, false);
        $this->assertNull($result);
    }
 
    public function testHora()
    {
        $result = ON\Text::hora('1903-02-01 01:02:03');
        $this->assertEquals('01:02:03', $result);
    }
 
    public function testHoraNotNull()
    {
        $result = ON\Text::hora();
        $this->assertEquals(date('H:i:s'), $result);
    }
 
    public function testHoraNull()
    {
        $result = ON\Text::hora(null, false);
        $this->assertNull($result);
    }
 
    public function testDataHora()
    {
        $result = ON\Text::dataHora('1903-02-01 01:02:03');
        $this->assertEquals('01/02/1903 01:02:03', $result);
    }
 
    public function testDataHoraNotNull()
    {
        $result = ON\Text::dataHora();
        $this->assertEquals(date('d/m/Y H:i:s'), $result);
    }
 
    public function testDataHoraNull()
    {
        $result = ON\Text::dataHora(null, false);
        $this->assertNull($result);
    }
 
    public function testSaudacao()
    {
         
        $hora = date('H');
        if (($hora >= 6) && ($hora < 12)):
                $saudacao = 'Bom Dia';
        elseif (($hora >= 12) && ($hora < 18)):
                $saudacao = 'Boa Tarde';
        elseif (($hora >= 18) || ($hora < 6)):
                $saudacao = 'Boa Noite';
        endif;
         
        $result = ON\Text::saudacao();
        $this->assertEquals($saudacao, $result);
    }
 
    public function testGetpwd()
    {
        define('BASE_URL', '');
        $result = ON\Text::getpwd(array('link1'=>'desc1','link2'=>'desc2'));
        $html = '<span class="url_pwd">/<a href="/">home</a>';
        $html.= '/';
        $html.= '<a href="/link1">';
        $html.= 'desc1';
        $html.= '</a>';
        $html.= '/';
        $html.= '<a href="/link2" style="font-weight: bold;">';
        $html.= 'desc2';
        $html.= '</a>';
        $html.= '</span><br />';
         
        $this->assertEquals($html, $result);
    }
 
    public function testInflector()
    {
        $result = ON\Text::inflector('teste', 2, false, false);
        $this->assertEquals('testes', $result);
         
        $result = ON\Text::inflector('teste', 2, false, true);
        $this->assertEquals('2 testes', $result);
         
        $result = ON\Text::inflector('par', 2, true, false);
        $this->assertNull($result);
    }
 
 
    public function testRemoveAcentos()
    {
        $result = ON\Text::removeAcentos('');
        $this->assertEquals('', $result);
    }
 
    public function testT()
    {
        define('TESTE', 'testT');
        $result = ON\Text::t('TESTE', false);
        $this->assertEquals('testT', $result);
         
        $result = ON\Text::t('TESTE');
        $this->assertNull($result);
    }
 
    public function testLang()
    {
        define('LANG_TESTE', 'testLang');
        $result = ON\Text::lang('TESTE', false);
        $this->assertEquals('testLang', $result);
    }
 
    public function testHttplink($link = null)
    {
        $result = ON\Text::httplink('github.com/');
        $this->assertEquals('http://github.com/', $result);
         
        $result = ON\Text::httplink();
        $this->assertNull($result);
    }
 
    public function testMascara()
    {
        $result = ON\Text::mascara('123456', '##-##');
        $this->assertEquals('12-34', $result);
    }
 
    public function testMascaraError()
    {
        $this->expectException('ON\Exception');
        $result = ON\Text::mascara('teste');
    }
}