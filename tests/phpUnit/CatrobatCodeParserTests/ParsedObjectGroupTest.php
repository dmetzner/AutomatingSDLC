<?php

namespace tests\CatrobatCodeParserTests;

use App\Catrobat\Services\CatrobatCodeParser\ParsedObject;
use App\Catrobat\Services\CatrobatCodeParser\ParsedObjectGroup;

class ParsedObjectGroupTest extends \PHPUnit\Framework\TestCase
{
  /**
   * @var ParsedObjectGroup
   */
  protected $group;

  public function setUp(): void
  {
    $xml_properties = simplexml_load_file(__DIR__ . '/Resources/ValidPrograms/AllBricksProgram/code.xml');
    $this->group = new ParsedObjectGroup($xml_properties->xpath('//object[@type="GroupSprite"]')[0]);
  }

  /**
   * @test
   * @dataProvider provideMethodNames
   */
  public function mustHaveMethod($method_name)
  {
    $this->assertTrue(method_exists($this->group, $method_name));
  }

  public function provideMethodNames()
  {
    return [
      ['getName'],
      ['addObject'],
      ['getObjects'],
      ['isGroup'],
    ];
  }

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function isGroupMustReturnTrue()
  {
    $this->assertTrue($this->group->isGroup());
  }

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function getObjectsMustReturnArrayOfParsedObject()
  {
    $expected = 'App\Catrobat\Services\CatrobatCodeParser\ParsedObject';

    $this->assertTrue($this->group->getObjects() === []);

    foreach($this->group->getObjects() as $actual) {
        $this->assertInstanceOf($expected, $actual);
    }

  }

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function addObjectMustAddObjectToObjects()
  {
    $xml_properties = simplexml_load_file(__DIR__ . '/Resources/ValidPrograms/AllBricksProgram/code.xml');
    $this->group->addObject(new ParsedObject($xml_properties->xpath('//object')[0]));
    $this->assertNotEmpty($this->group->getObjects());
  }

  /**
   * @test
   * @depends mustHaveMethod
   */
  public function getNameMustReturnCertainString()
  {
    $expected = 'TestGroup';
    $actual = $this->group->getName();

    $this->assertEquals($expected, $actual);
  }
}