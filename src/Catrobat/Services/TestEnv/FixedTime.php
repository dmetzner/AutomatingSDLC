<?php

namespace App\Catrobat\Services\TestEnv;

use App\Catrobat\Services\Time;

/**
 * Class FixedTime.
 */
class FixedTime extends Time
{
  /**
   * @var
   */
  protected $timestamp;

  /**
   * FixedTime constructor.
   *
   * @param $timestamp
   */
  public function __construct($timestamp)
  {
    $this->timestamp = $timestamp;
  }

  /**
   * @return int
   */
  public function getTime()
  {
    return $this->timestamp;
  }
}
