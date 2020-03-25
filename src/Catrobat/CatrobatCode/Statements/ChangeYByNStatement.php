<?php

namespace App\Catrobat\CatrobatCode\Statements;

/**
 * Class ChangeYByNStatement.
 */
class ChangeYByNStatement extends BaseChangeByNStatement
{
  const BEGIN_STRING = 'Y';
  const END_STRING = ')<br/>';

  /**
   * ChangeYByNStatement constructor.
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
    $formula_string = $this->getFormulaListChildStatement()->executeChildren();
    $formula_string_without_markup = preg_replace('#<[^>]*>#', '', $formula_string);

    return 'Change Y by '.$formula_string_without_markup;
  }

  /**
   * @return string
   */
  public function getBrickColor()
  {
    return '1h_brick_blue.png';
  }
}
