<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * App\Models\Levels
 *
 * @mixin \Eloquent
 */
class Levels extends Model {
  /**
   * Получение массива разрешённых функций
   * @param object $user
   * @return object
   */
  static function getLevels($user) {
    $levels = DB::table('access')
      ->leftJoin('level', 'level.level_id', '=', 'access.level_id')
      ->select('level.name')
      ->where('group_id', '=', $user->status)
      ->pluck('level.name')
      ->toArray();
    return $levels;
  }


}
