<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Image;
use DB;

/**
 * App\Models\scatDrive
 *
 * @mixin \Eloquent
 */
class scatDrive extends Model {

  static function scatAdd($scatPhoto, $postData = array()) {

    $id = DB::table('catalog_events')->insertGetId($postData);
    
    if($id>0){
      $img      = Image::make($scatPhoto);
      $full     = public_path() . '/data/s_catalog/full/';
      $prem     = public_path() . '/data/s_catalog/prem/';
      $publ     = public_path() . '/data/s_catalog/publ/';
      $filename = 'Photo_'.$id.'_'.$postData['event_alias']; 
      $resol    = 'jpg';
      //$resol    = $scatPhoto->getClientOriginalExtension(); //======= Возвращает расширение файла 
      
      DB::table('catalog_events_img')->insert([ 'id_master'=> $id, 'id_master'=> $id, 'event_img_name'=> $filename, 'event_img_res'=> $resol, 'event_status_img'=> 1 ]); 
      $img->resize(800, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });
      $img->save($full . $filename.'.'.$resol);

      $img->resize(400, null, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
      });
      $img->save($prem . $filename.'.'.$resol);

      $img->fit(64, 45, function ($constraint) { 
        $constraint->upsize();
      });
      $img->save($publ . $filename.'.'.$resol);      
    } 
    return $id;
  }

  static function outList($idPart) {  
    return DB::table('catalog_events')
                    ->leftJoin('catalog_events_img', 'catalog_events.id', '=', 'catalog_events_img.id_master', 'AND', 'catalog_events_img.event_status_img', '=', 1)
                    ->where('catalog_events.id_part', '=', $idPart)
                    ->orderBy('catalog_events.event_data', 'DESC')
                    ->get();
  }
 
  static function scatUnitStatus($id) {  
    $status = DB::table('catalog_events')->select('catalog_events.event_status')->where('catalog_events.id', '=', $id)->get();
    
    if($status[0]->event_status == 1){
      $_st = 0;
    } else {
      $_st = 1;
    }
    DB::table('catalog_events')->where('id', $id)->update(['event_status' => $_st]);
    return $_st;
  }
  
  static function delUnitStatus($id) {  
              DB::table('catalog_events')->where('catalog_events.id', '=', $id)->delete();  
    $images = DB::table('catalog_events_img')->where('id_master', $id)->get();    
    if( count($images)>0 ) {
      foreach( $images AS $list){       
        @unlink( $_SERVER['DOCUMENT_ROOT'].'/data/s_catalog/full/'.$list->event_img_name.'.'.$list->event_img_res );
        @unlink( $_SERVER['DOCUMENT_ROOT'].'/data/s_catalog/prem/'.$list->event_img_name.'.'.$list->event_img_res );
        @unlink( $_SERVER['DOCUMENT_ROOT'].'/data/s_catalog/publ/'.$list->event_img_name.'.'.$list->event_img_res );       
      }  
    } 
    DB::table('catalog_events_img')->where('id_master', $id)->delete();
  }

  static function outUnit($idUnit) {  
    return DB::table('catalog_events')
                    ->leftJoin( 'catalog_events_img', 'catalog_events.id', '=', 'catalog_events_img.id_master' )
                    ->where('catalog_events.id', '=', $idUnit) 
                    ->get();
  } 
  
  static function showUnitScat($id) {   
    $date['info']  = DB::table('catalog_events')->where('id', '=', $id)->get();
    $date['photo'] = DB::table('catalog_events_img')->where('id_master', '=', $id)->get();  
    return $date; 
  }
   
  
  
  
  
  
  
}
