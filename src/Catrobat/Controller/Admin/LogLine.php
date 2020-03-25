<?php

namespace App\Catrobat\Controller\Admin;

/**
 * Class LogLine.
 */
class LogLine
{
  /**
   * @var string
   */
  public $date = '';

  /**
   * @var string
   */
  public $debug_code = '';

  /**
   * @var int
   */
  public $debug_level = 0;

  /**
   * @var string
   */
  public $msg = '';

  /**
   * LogLine constructor.
   *
   * @param null $line
   */
  public function __construct($line = null)
  {
    if (null === $line)
    {
      $this->msg = 'No Logs with Loglevel';
      $this->debug_code = 'No search results';
    }
    else
    {
      $this->date = $this->getSubstring($line, ']', true);
      $line = substr($line, strlen($this->date) + 1);
      $this->debug_code = $this->getSubstring($line, ':');
      $line = substr($line, strlen($this->debug_code) + 2);
      $this->msg = $line;

      $this->debug_level = $this->getDebugLevel($this->debug_code);
    }
  }

  /**
   * @param      $string
   * @param      $needle
   * @param bool $last_char
   *
   * @return bool|string
   */
  private function getSubstring($string, $needle, $last_char = false)
  {
    $pos = strpos($string, $needle);

    if (false === $pos)
    {
      return '';
    }
    if ($last_char)
    {
      $pos = $pos + 1;
    }

    return substr($string, 0, $pos);
  }

  /**
   * @param $string
   *
   * @return int
   */
  private function getDebugLevel($string)
  {
    $pos = strpos($string, '.');
    $extracted_string = substr($string, $pos + 1);

    switch ($extracted_string)
    {
      case 'INFO':
        $debug_level = LogsController::FILTER_LEVEL_INFO;
        break;
      case 'WARNING':
        $debug_level = LogsController::FILTER_LEVEL_WARNING;
        break;
      case 'ERROR':
        $debug_level = LogsController::FILTER_LEVEL_ERROR;
        break;
      case 'CRITICAL':
        $debug_level = LogsController::FILTER_LEVEL_CRITICAL;
        break;
      case 'NOTICE':
        $debug_level = LogsController::FILTER_LEVEL_NOTICE;
        break;
      case 'ALERT':
        $debug_level = LogsController::FILTER_LEVEL_ALERT;
        break;
      case 'EMERGENCY':
        $debug_level = LogsController::FILTER_LEVEL_EMERGENCY;
        break;
      case 'DEBUG':
        $debug_level = LogsController::FILTER_LEVEL_DEBUG;
        break;
      default:
        $debug_level = LogsController::FILTER_LEVEL_DEBUG;
    }

    return $debug_level;
  }
}
