<?php

namespace App\Models\Adm;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    //
    static function getRegionByTelephone($telephone) {
        return DB::table('telephone_has_region')
            ->where('telephone', $telephone)
            ->limit(1)
            ->get();
    }

    //
    static function checkChangeAndUpdate($telephone, $region){
        $regionOld = DB::table('telephone_has_region')
          ->where('telephone', $telephone)
          ->limit(1)
          ->get();
        if(count($regionOld) > 0){
          if($region != $regionOld[0]->region){
            DB::table('telephone_has_region')
              ->where('telephone', $telephone)
              ->update([
                'region' => $region,
              ]);
          }
        }else{
          $data['region'] = $region;
          $data['telephone'] = $telephone;
          DB::table('telephone_has_region')->insert($data);
        }
    }
}
