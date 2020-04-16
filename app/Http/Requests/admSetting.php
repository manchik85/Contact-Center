<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class admSetting extends FormRequest {
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

      'settinng_st' => 'integer|max:10',
      'usd' => 'numeric|max:255',
      'eur' => 'numeric|max:255',
      'mail' => 'max:255',
      'skype' => 'max:255',
      'slogan' => 'max:255',
      'adr' => 'max:255',
      'instagram' => 'max:255',
      'vk' => 'max:255',
      'facebook' => 'max:255',
      'odnoklassniki' => 'max:255',
      'twitter' => 'max:255',
      'google' => 'max:255',

    ];
  }
}
