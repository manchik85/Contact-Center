<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userAcauntPostAdm extends FormRequest {
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
      'name' => 'max:255',
      'email' => 'email|max:255',

      'instagram' => 'max:255',
      'vk' => 'max:255',
      'facebook' => 'max:255',
      'odnoklassniki' => 'max:255',
      'twitter' => 'max:255',
      'google' => 'max:255',

    ];
  }
}
