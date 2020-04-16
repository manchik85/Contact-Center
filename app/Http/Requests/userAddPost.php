<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userAddPost extends FormRequest {
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

      'name' => 'required|max:255',
      'email' => 'required|email|max:255|unique:users',
      'password' => 'required|min:4|confirmed',
      'level_user' => 'integer|max:90',
      'users_cont_phone' => 'max:255',
      'users_phone' => 'max:255',

    ];
  }
}
