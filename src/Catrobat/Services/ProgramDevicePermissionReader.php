<?php

namespace App\Catrobat\Services;

/**
 * Class ProgramDevicePermissionReader.
 */
class ProgramDevicePermissionReader
{
  /**
   * @param $filepath
   *
   * @return array|bool
   */
  public function getPermissions($filepath)
  {
    @$permissions = file('zip://'.$filepath.'#permissions.txt', FILE_IGNORE_NEW_LINES);
    if (false === $permissions)
    {
      return [];
    }

    return $permissions;
  }
}
