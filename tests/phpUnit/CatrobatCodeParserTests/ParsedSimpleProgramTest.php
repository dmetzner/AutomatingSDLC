<?php

namespace tests\CatrobatCodeParserTests;

use App\Catrobat\Services\CatrobatCodeParser\ParsedSimpleProgram;

class ParsedSimpleProgramTest extends \PHPUnit\Framework\TestCase
{
  protected $program;

  public function setUp(): void
  {
    $xml_properties = simplexml_load_file(__DIR__ . '/Resources/ValidPrograms/SimpleProgram/code.xml');
    $this->program = new ParsedSimpleProgram($xml_properties);
  }

  /**
   * @test
   * @dataProvider provideMethodNames
   */
  public function mustHaveMethod($method_name)
  {
    $this->assertTrue(method_exists($this->program, $method_name));
  }

  public function provideMethodNames()
  {
    return [
      ['hasScenes'],
      ['getCodeStatistic'],
    ];
  }

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function hasScenesMustReturnFalse()
  {
    $this->assertFalse($this->program->hasScenes());
  }

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function getCodeStatisticMustReturnCodeStatistic()
  {
    $actual = $this->program->getCodeStatistic();
    $expected = 'App\Catrobat\Services\CatrobatCodeParser\CodeStatistic';

    $this->assertInstanceOf($expected, $actual);
  }
}