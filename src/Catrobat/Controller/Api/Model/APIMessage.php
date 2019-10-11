<?php


namespace App\Catrobat\Controller\Api\Model;


/**
 * @OA\Schema(@OA\Xml(name="APIMessage"))
 */
class APIMessage
{
  /**
   * @OA\Property(example="The operation was successful")
   * @var string
   */
  public $message;
}