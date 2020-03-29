<?php

namespace App\Catrobat\CatrobatCode\Statements;

class FormulaListStatement extends Statement
{
  const X_POSITION = 'X_POSITION';

  const Y_POSITION = 'Y_POSITION';

  private $xPosition;

  private $yPosition;

  /**
   * FormulaListStatement constructor.
   *
   * @param mixed $statementFactory
   * @param mixed $xmlTree
   * @param mixed $spaces
   */
  public function __construct($statementFactory, $xmlTree, $spaces)
  {
    parent::__construct($statementFactory, $xmlTree, $spaces - 1,
      '', '');
  }

  public function executeChildren(): string
  {
    $code = '';
    $counter = 0;

    $statementCount = count($this->statements);
    foreach ($this->statements as $value)
    {
      ++$counter;

      $code .= $value->execute();
      if ($counter < $statementCount)
      {
        $code .= ', ';
      }
    }

    return $code;
  }

  public function executePlaceAtFormula(): string
  {
    $code = '';
    $endCode = '';

    $this->setVariables();

    if (null != $this->xPosition)
    {
      $code .= 'X('.$this->xPosition->execute().')';
    }

    if (null != $this->xPosition && null != $this->yPosition)
    {
      $code .= ', ';
    }

    if (null != $this->yPosition)
    {
      $code .= 'Y('.$this->yPosition->execute().')';
    }

    return $code.$endCode;
  }

  protected function setVariables()
  {
    foreach ($this->statements as $value)
    {
      if ($value instanceof FormulaStatement)
      {
        if (self::X_POSITION == $value->getCategory())
        {
          $this->xPosition = $value;
        }
        else
        {
          if (self::Y_POSITION == $value->getCategory())
          {
            $this->yPosition = $value;
          }
        }
      }
    }
  }
}
