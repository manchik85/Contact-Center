<?php

namespace App\Models\Adm;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

set_time_limit(250000);

/**
 * App\Models\Adm\Users
 *
 * @mixin \Eloquent
 */
class Email extends Model
{
  static function addMail($data)
  {
    return DB::table('mailing_list')
      ->insertGetId($data);
  }


  static function getAllEmails()
  {
    return DB::table('mailing_list')

      ->leftJoin('users',      'users.id', '=', 'mailing_list.users_id')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->leftJoin('users_client', 'users_client.client_mail', '=', 'mailing_list.mail_adress')
      ->select([
        'mailing_list.id          as mailing_list_id',
        'mailing_list.mail_adress as mail_adress',
        'mailing_list.mail_title  as mail_title',
        'mailing_list.mail_body   as mail_body',
        'mailing_list.mail_date   as mail_date',

        'users_info.users_phone   as users_phone',

        'users.name               as name',
        'users.email              as email',

        'users_client.client_fio   as client_fio',
        'users_client.gov_name     as gov_name',
        'users_client.client_spot  as client_spot',
        'users_client.client_mail  as mail_adress',

      ])
      ->orderByDesc('mailing_list.mail_date')
      ->paginate(100);
  }


  static function getMyEmails($id)
  {
    return DB::table('mailing_list')

      ->leftJoin('users',      'users.id', '=', 'mailing_list.users_id')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->leftJoin('users_client', 'users_client.client_mail', '=', 'mailing_list.mail_adress')
      ->select([
        'mailing_list.id          as mailing_list_id',
        'mailing_list.mail_adress as mail_adress',
        'mailing_list.mail_title  as mail_title',
        'mailing_list.mail_body   as mail_body',
        'mailing_list.mail_date   as mail_date',

        'users_info.users_phone   as users_phone',

        'users.name               as name',
        'users.email              as email',

        'users_client.client_fio   as client_fio',
        'users_client.gov_name     as gov_name',
        'users_client.client_spot  as client_spot',
        'users_client.client_mail  as mail_adress',

      ])
      ->orderByDesc('mailing_list.mail_date')
      ->where('mailing_list.users_id', $id)
      ->paginate(100);
  }


  static function getAllEmail($id)
  {
    return DB::table('mailing_list')
      ->leftJoin('users',      'users.id', '=', 'mailing_list.users_id')
      ->leftJoin('users_info', 'users.id', '=', 'users_info.users_id')
      ->leftJoin('users_client', 'users_client.client_mail', '=', 'mailing_list.mail_adress')
      ->orderByDesc('mailing_list.mail_date')
      ->where('mailing_list_id', $id)
      ->get();
  }

  static function delEmails($data)
  {

    DB::table('mailing_list')
      ->where('mailing_list_id', $data['id'])
      ->delete();
  }


}





































