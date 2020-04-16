<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class admCatalogLite extends FormRequest {
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
 
      'unit_title_add' => 'max:350',
      'unit_description_add' => 'max:550',
      'unit_title' => 'max:350',
      'unit_description' => 'max:550',
    ];
  }
}
