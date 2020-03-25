<?php

namespace App\Catrobat\CatrobatCode\Statements;

/**
 * Class ComeToFrontStatement.
 */
class ComeToFrontStatement extends Statement
{
  const BEGIN_STRING = 'come to front';
  const END_STRING = '<br/>';

  /**
   * ComeToFrontStatement constructor.
   *
   * @param $statementFactory
   * @param $xmlTree
   * @param $spaces
   */
  public function __construct($statementFactory, $xmlTree, $spaces)
  {
    parent::__construct($statementFactory, $xmlTree, $spaces,
      self::BEGIN_STRING,
      self::END_STRING);
  }

  /**
   * @return string
   */
  public function getBrickText()
  {
    return 'Go to front';
  }

  /**
   * @return string
   */
  public function getBrickColor()
  {
    return '1h_brick_blue.png';
  }
}
