<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class modalCallBack extends FormRequest {
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

      'name'    => 'max:50',
      'phone'   => 'required|max:50',
      'comment' => 'max:500',

    ];
  }
}


/**
 *
 *
 *
 *
name: $('#name').val(),
phone: $('#phone').val(),
comment: $('#comment').val(),
 */
