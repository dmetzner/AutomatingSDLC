<?php

namespace tests\CatrobatCodeParserTests;

use App\Catrobat\Services\CatrobatCodeParser\ParsedSceneProgram;

class ParsedSceneProgramTest extends \PHPUnit\Framework\TestCase
{
  protected $program;

  public function setUp(): void
  {
    $xml_properties = simplexml_load_file(__DIR__ . '/Resources/ValidPrograms/SceneProgram/code.xml');
    $this->program = new ParsedSceneProgram($xml_properties);
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
      ['getScenes'],
    ];
  }

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function hasScenesMustReturnTrue()
  {
    $this->assertTrue($this->program->hasScenes());
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

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function getScenesMustReturnArrayOfScenes()
  {
    $expected = 'App\Catrobat\Services\CatrobatCodeParser\ParsedScene';

    foreach ($this->program->getScenes() as $actual)
      $this->assertInstanceOf($expected, $actual);
  }
}