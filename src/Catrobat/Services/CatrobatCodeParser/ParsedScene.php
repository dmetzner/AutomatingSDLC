<?php

namespace App\Catrobat\Services\CatrobatCodeParser;

use SimpleXMLElement;
use Symfony\Component\Config\Definition\Exception\Exception;

class ParsedScene extends ParsedObjectsContainer
{
  /**
   * @var SimpleXMLElement
   */
  protected $name;

  public function __construct(SimpleXMLElement $scene_xml_properties)
  {
    parent::__construct($scene_xml_properties);

    if (0 === count($scene_xml_properties->name))
    {
      throw new Exception('Scene without name');
    }

    $this->name = $scene_xml_properties->name;
  }

  public function getName(): SimpleXMLElement
  {
    return $this->name;
  }
}
