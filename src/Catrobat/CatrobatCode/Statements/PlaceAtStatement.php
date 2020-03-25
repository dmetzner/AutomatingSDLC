<?php

namespace App\Catrobat\CatrobatCode\Statements;

/**
 * Class PlaceAtStatement.
 */
class PlaceAtStatement extends Statement
{
  const BEGIN_STRING = 'place at ';
  const END_STRING = '<br/>';

  /**
   * PlaceAtStatement constructor.
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
  public function executeChildren()
  {
    $code = '';

    foreach ($this->statements as $value)
    {
      if ($value instanceof FormulaListStatement)
      {
        $code .= $value->executePlaceAtFormula();
      }
    }

    return $code;
  }

  /**
   * @return string
   */
  public function getBrickText()
  {
    foreach ($this->getFormulaListChildStatement()->getStatements() as $statement)
    {
      if ($statement instanceof FormulaStatement)
      {
        switch ($statement->getCategory())
        {
          case 'Y_POSITION':
            $formula_y_dest = $statement->execute();
            break;
          case 'X_POSITION':
            $formula_x_dest = $statement->execute();
            break;
        }
      }
    }

    $formula_x_dest_no_markup = preg_replace('#<[^>]*>#', '', $formula_x_dest);
    $formula_y_dest_no_markup = preg_replace('#<[^>]*>#', '', $formula_y_dest);

    return 'Place at X: '.$formula_x_dest_no_markup.' Y: '.$formula_y_dest_no_markup;
  }

  /**
   * @return string
   */
  public function getBrickColor()
  {
    return '1h_brick_blue.png';
  }
}
