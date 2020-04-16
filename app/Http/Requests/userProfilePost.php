<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userProfilePost extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [

      'face' => 'min:2|max:5',
      'jur-name' => 'max:255',
      'jur-birthday' => 'max:255',
      'fiz-name' => 'max:255',
      'fiz-birthday' => 'max:255',
      'evCity' => 'required',
      'fiz-bik-inn' => 'max:25',
      'mob_phone' => 'required|max:255',
      'st_phone' => 'max:255',
      'cb_status' => 'required|array',
      'cb_business_region' => 'required|array',
      'cb_goods' => 'array',
      'cb_services' => 'array',
      'cb_proizv_obekty' => 'array',

    ];
  }
}
