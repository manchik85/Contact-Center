<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

/**
 * App\Models\DatePreparation
 *
 * @mixin \Eloquent
 */
class DatePreparation extends Model {

  static function preparation($date='') {

    $_m = explode(' ', $date);
    $_n = explode('.', $_m[0]);
    $result = strtotime($_n[2].'-'.$_n[1].'-'.$_n[0].' '.$_m[1]);

    return $result;
  }
  
}
