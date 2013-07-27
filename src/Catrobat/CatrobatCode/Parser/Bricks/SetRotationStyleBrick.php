<?php

namespace App\Catrobat\CatrobatCode\Parser\Bricks;

use App\Catrobat\CatrobatCode\Parser\Constants;

class SetRotationStyleBrick extends Brick
{
  protected function create(): void
  {
    $this->type = Constants::SET_ROTATION_STYLE_BRICK;
    $this->caption = 'Set rotation style';
    $this->setImgFile(Constants::MOTION_BRICK_IMG);
  }
}
