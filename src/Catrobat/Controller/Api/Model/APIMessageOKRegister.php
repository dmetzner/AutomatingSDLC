<?php


namespace App\Catrobat\Controller\Api\Model;

/**
 * @OA\Schema(@OA\Xml(name="APIMessageOKRegister"))
 */
class APIMessageOKRegister extends APIMessage
{
  /**
   * @OA\Property(example="76ee3de97a1b8b903319b7c013d8c877")
   * @var string
   */
  public $token;
}